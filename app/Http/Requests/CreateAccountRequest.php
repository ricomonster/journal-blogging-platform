<?php //-->
namespace Journal\Http\Requests;

class CreateAccountRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email'     => 'required|unique:users,email',
			'password'  => 'required|min:6',
			'name'      => 'required'
		];
	}

	/**
	 * Customize the error messages
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'email.required' 	=> 'We need your email.',
			'email.unique' 		=> 'Your email already exists',
			'email.email' 		=> 'Email is not a valid email',
			'password.required' => 'We need a password',
			'password.min' 		=> 'Password should be :min+ characters',
			'name.required' 	=> 'Your name is required'
		];
	}

}
