<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['first_name', 'last_name', 'email_address', 'password', 'screen_name', 'token',
'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = '';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'first_name' => 'required|regex_match[/^[a-zA-Z\-]*$/]|min_length[3]',
        'last_name' => 'required|regex_match[/^[a-zA-Z\-]*$/]|min_length[3]',
        'email_address' => 'required|valid_email|is_unique[users.email_address]',
        'screen_name' => 'required|regex_match[/^[a-zA-Z0-9\_]+$/]|max_length[15]',
        'password' => 'required|min_length[8]',
        'confirm_password' => 'required_with[password]|matches[password]'
    ];
    protected $validationMessages   = [
        'first_name' => [
            'required' => 'First name is required!',
            'regex_match' => 'First name contains at least one invalid character!',
            'min_length' => 'First name should be 3 characters minimum'
        ],
        'last_name' => [
            'required' => 'Last name is required!',
            'regex_match' => 'Last name contains at least one invalid character!',
            'min_length' => 'Last name should be 3 characters minimum'
        ],
        'email_address' => [
            'required' => 'Email address is required!',
            'valid_email' => 'Email address contains at least one invalid character!',
            'is_unique' => 'Email address is already taken!'
        ],
        'screen_name' => [
            'required' => 'Screen name is required!',
            'regex_match' => 'Screen name contains at least one invalid character!',
            'max_length' => 'Screen name should be 15 characters maximum'
        ],
        'password' => [
            'required' => 'Password is required!',
            'min_length' => 'Password should be 8 characters minimum!',
        ],
        'confirm_password' => [
            'required_with' => 'Confirm password is required!',
            'matches' => 'Passwords don\'t match!'
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['beforeInsert'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['beforeUpdate'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    
    
    
    protected function beforeInsert(array $data)
    {
        return $this->hashPassword($data);
    }
    
    protected function beforeUpdate(array $data)
    {
        return $this->hashPassword($data);
    }
    
    /**
     * Password hash method
     *
     * @param  array  $data
     *
     * @return array
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        
        return $data;
    }
}
