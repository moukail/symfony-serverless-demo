<?php

namespace App\Logger\Handler;

use Aws\CloudWatchLogs\CloudWatchLogsClient;
use DateTimeZone;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\LogRecord;

class CloudWatchLogsHandler extends AbstractProcessingHandler
{
    /**
     * Requests per second limit (https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/cloudwatch_limits_cwl.html).
     */
    public const RPS_LIMIT = 5;

    /**
     * Event size limit (https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/cloudwatch_limits_cwl.html).
     *
     * @var int
     */
    public const EVENT_SIZE_LIMIT = 262118; // 262144 - reserved 26

    /**
     * The batch of log events in a single PutLogEvents request cannot span more than 24 hours.
     *
     * @var int
     */
    public const TIMESPAN_LIMIT = 86400000;

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * @var string
     */
    private $sequenceToken;

    /**
     * @var array
     */
    private $buffer = [];

    /**
     * Data amount limit (http://docs.aws.amazon.com/AmazonCloudWatchLogs/latest/APIReference/API_PutLogEvents.html).
     *
     * @var int
     */
    private $dataAmountLimit = 1048576;

    /**
     * @var int
     */
    private $currentDataAmount = 0;

    /**
     * @var int
     */
    private $remainingRequests = self::RPS_LIMIT;

    /**
     * @var \DateTime
     */
    private $savedTime;

    /**
     * @var int|null
     */
    private $earliestTimestamp;

    /**
     * CloudWatchLogs constructor.
     *
     * @param CloudWatchLogsClient $client
     *
     *  Log group names must be unique within a region for an AWS account.
     *  Log group names can be between 1 and 512 characters long.
     *  Log group names consist of the following characters: a-z, A-Z, 0-9, '_' (underscore), '-' (hyphen),
     * '/' (forward slash), and '.' (period).
     * @param string $group
     *
     *  Log stream names must be unique within the log group.
     *  Log stream names can be between 1 and 512 characters long.
     *  The ':' (colon) and '*' (asterisk) characters are not allowed.
     *
     * @throws \Exception
     */
    public function __construct(
        private readonly CloudWatchLogsClient $client,
        private readonly string $group,
        private readonly string $stream,
    ) {
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
                'timestamp' => round(microtime(true) * 1000)
            ]]
        ];

        $response = $this->client->putLogEvents($data);
        $data = $response->get('data');
    }

    private function initializeGroup(): void
    {
        // fetch existing groups
        $existingGroups = $this->client->describeLogGroups([
            'logGroupNamePrefix' => $this->group
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
                'logStreamName' => $this->stream
            ]);
        }
    }
}
