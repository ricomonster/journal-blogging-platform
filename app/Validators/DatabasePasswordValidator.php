<?php //-->
namespace Journal\Validators;

use Illuminate\Validation\Validator;
use Journal\User;
use Hash;

class DatabasePasswordValidator extends Validator
{
    public function validateDatabasePassword($attribute, $value, $parameters)
    {
        // get the user
        $result = User::where('id', '=', $parameters[0])->first();
        return Hash::check($value, $result->password);
    }
}
