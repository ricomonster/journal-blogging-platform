<?php namespace Journal\Http\Requests;

use Journal\Http\Requests\Request;

class SetupBlogRequest extends Request {

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
			'blog_title' 	=> 'required',
			'theme' 		=> 'required'
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
			'blog_title.required' 	=> 'Your blog should have a title.',
			'theme.required' 		=> 'Your blog needs some skin.'
		];
	}
}
