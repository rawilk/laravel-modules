<?php return '<?php

namespace Modules\\Blog\\CustomPath;

use Illuminate\\Contracts\\Validation\\Rule;

class UniqueRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return \'The validation error message\';
    }
}
';
