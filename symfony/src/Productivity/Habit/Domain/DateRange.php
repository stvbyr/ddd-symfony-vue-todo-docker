<?php

declare(strict_types=1);

namespace Productivity\Habit\Domain;

use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use JsonSerializable;
use Productivity\Habit\Domain\Exception\WrongRangeException;

class DateRange implements JsonSerializable
{
    public const DATE_FORMAT = 'Y-m-d';

    public function __construct(
        private DateTimeImmutable $from,
        private DateTimeImmutable $to,
        private Frequency $frequency,
    ) {
        if ($from->setTime(0, 0) > $to->setTime(0, 0)) {
            throw new WrongRangeException('The from date can\'t be bigger than the to date.');
        }

        if ($from->setTime(0, 0) == $to->setTime(0, 0)) {
            throw new WrongRangeException('From date and to date are not allowed to be the same.');
        }
    }

    public static function fromArray(array $dateRange): self
    {
        $dateRangeIsValid = (
            isset($dateRange['from']) &&
            isset($dateRange['to']) &&
            isset($dateRange['frequency'])
        );

        if (!$dateRangeIsValid) {
            throw new \Exception("'from', 'to' and 'frequency' have to present in the dateRange.");
        }

        return new self(...$dateRange);
    }

    public function getDaysInRange(): array
    {
        $period = new DatePeriod(
            $this->from->setTime(0, 0),
            new DateInterval($this->frequency->getIntervalString()),
            $this->to->setTime(0, 0)->modify('+1 day') // mofidy is used to include the last day as well
        );

        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date;
        }

        return $dates;
    }

    public function jsonSerialize(): array
    {
        return [
            'from' => $this->from->format(self::DATE_FORMAT),
            'to' => $this->to->format(self::DATE_FORMAT),
            'frequency' => $this->frequency->toString(),
        ];
    }

    public function toArray(): array
    {
        return json_decode(json_encode($this), true);
    }

    /**
     * Get the value of from.
     */
    public function getFrom(): DateTimeImmutable
    {
        return $this->from;
    }

    /**
     * Get the value of to.
     */
    public function getTo(): DateTimeImmutable
    {
        return $this->to;
    }

    /**
     * Get the value of frequency.
     */
    public function getFrequency(): Frequency
    {
        return $this->frequency;
    }
}
