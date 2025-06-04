<?php
namespace App\Models;
use CodeIgniter\Model;

class HomeModel extends Model
{
    
    /**
     * This function is used to check whether email address is already exist or not
     * @param {string} $email : This is email address
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email)
    {
        $this->db->select("email_address");
        $this->db->from("subscribers");
        $this->db->where("email_address", $email);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewSubscriber($subscriberInfo)
    {
        $this->db->trans_start();
        $this->db->insert('subscribers', $subscriberInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

}