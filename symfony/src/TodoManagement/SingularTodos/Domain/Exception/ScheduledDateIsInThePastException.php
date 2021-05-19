<?php

declare(strict_types=1);

namespace TodoManagement\SingularTodos\Domain\Exception;

use Exception;

final class ScheduledDateIsInThePastException extends Exception
{
}
