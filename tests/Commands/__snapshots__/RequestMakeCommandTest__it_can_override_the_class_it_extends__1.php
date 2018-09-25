<?php return '<?php

namespace Modules\\Blog\\Http\\Requests;

use Some\\Other\\RequestClass;

class CreateBlogPostRequest extends RequestClass
{
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
         return [];
     }
}
';
