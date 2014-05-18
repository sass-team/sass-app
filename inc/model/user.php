<?php 
/// Using Object Oriented Programming
class Users {

// connection to db.
private $db;
// constructor of this class
public function __construct($database) {
        $this->db = $database;
    } // end __construct

// gets user's information using the user's id.
/**
*
* small description -- one senteces. E.g. Qureis the the dtabase and returns all the data of users given his emial.
* @param email the email of user
* return an array with users data
*/
function get_data($email) {

    $query = $this->db->prepare("SELECT * FROM user
                                    LEFT OUTER JOIN user_types ON user.user_types_id = user_types.id
                                    LEFT OUTER JOIN major ON user.major_id = major.id
                                    WHERE email = ?
                                ");
    $query->bindValue(1, $email);

    try {
        $query->execute();
        return $query->fetch();
    } catch (PDOException $e) {
        die($e->getMessage());
    } // end try
} // end function get_data

}
?>
