<?php

namespace App\Logger\Handler;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class CloudWatchLogsHandler extends AbstractProcessingHandler
{
    public function __construct(
        private readonly CloudWatchLogsClient $client,
        private readonly string $group,
        private readonly string $stream,
    ) {
        parent::__construct(Level::Debug, true);

        $this->initializeGroup();
        $this->initializeStream();
    }

    protected function write(LogRecord $record): void
    {
        $data = [
            'logGroupName' => $this->group,
            'logStreamName' => $this->stream,
            'logEvents' => [[
                'message' => $record->message,
                'timestamp' => round(microtime(true) * 1000),
            ]],
        ];

        $response = $this->client->putLogEvents($data);
        $data = $response->get('data');
    }

    private function initializeGroup(): void
    {
        // fetch existing groups
        $existingGroups = $this->client->describeLogGroups([
            'logGroupNamePrefix' => $this->group,
        ])->get('logGroups');

        // extract existing groups names
        $existingGroupsNames = array_map(
            function ($group) {
                return $group['logGroupName'];
            },
            $existingGroups
        );

        // create group and set retention policy if not created yet
        if (!in_array($this->group, $existingGroupsNames, true)) {
            $createLogGroupArguments = ['logGroupName' => $this->group];
            $this->client->createLogGroup($createLogGroupArguments);
        }
    }

    private function initializeStream(): void
    {
        // fetch existing streams
        $existingStreams = $this->client->describeLogStreams([
            'logGroupName' => $this->group,
            'logStreamNamePrefix' => $this->stream,
        ])->get('logStreams');

        // extract existing streams names
        $existingStreamsNames = array_map(
            function ($stream) {
                return $stream['logStreamName'];
            },
            $existingStreams
        );

        if (!in_array($this->stream, $existingStreamsNames, true)) {
            $this->client->createLogStream([
                'logGroupName' => $this->group,
                'logStreamName' => $this->stream,
            ]);
        }
    }
}
