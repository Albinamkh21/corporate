<?php

namespace Corp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       return \Auth::user()->canDo('EDIT_USERS');
    }
	
	protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
    
	    $validator->sometimes('password', 'required|min:6|confirmed', function($input)
	    {
			
			if(!empty($input->password) || ((empty($input->password) && $this->route()->getName() !== 'admin.users.update'))) {
				return TRUE;
			}
			
			return FALSE;
	    });
        $validator->sometimes('email','unique:users', function($input) {
            if($this->route()->getName() == 'admin.users.update') {
                //$model = $this->route()->parameter('article');
                return false;
            }

            return true;

        });

       // dd($validator);
	    return $validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = (isset($this->route()->parameter('users')->id)) ? $this->route()->parameter('users')->id : '';
		
		return [
             'name' => 'required|max:255',
			 'login' => 'required|max:255',
			 'role_id' => 'required|integer',
             'email' => 'required|email|max:255'
            //'email' => 'required|email|max:255|unique:users,email,'.$id
        ];
    }
}
