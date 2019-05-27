<?php
class Project
{
	private $conn = NULL;
	public function __construct()
    {
        $dbhost = "localhost";
        $dbname = "farmgame";
        $dbuser = "root";
        $dbpass = "";
        try
        {
            $this->conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
            if(!$this->conn)
            {
                die("No Active Database connection object found");
            }
        }
        catch( Exception $e )
        {
            die("Database Error: " . $e->getMessage( ) . " " . $e->getCode( ));
        }
    }
	public function insertPlayer()
    {
        $random_string = genearte_random_string(5);
        if(setcookie('player',$random_string,time() + (10 * 365 * 24 * 60 * 60)))
        {
            $alive_string = "1,2,3,4,5,6,7";
            $sql = "INSERT INTO players(player_id,alive_string) VALUES (:player_id,:alive_string)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':player_id',$random_string);
            $stmt->bindParam(':alive_string',$alive_string);
            if($stmt->execute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    public function getRounds()
    {
        $player_id = $_COOKIE['player'];
        $result = 0;
        $sql = "select round_no from rounds where player_id='".$player_id."' order by round_no desc limit 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        while($row = $stmt->fetch())
        {
    		$result = $row['round_no'];
		}
        return $result;
    }
    public function getAliveEntities()
    {
        $player_id = $_COOKIE['player'];
        $sql = "select alive_string from players where player_id='".$player_id."'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        while($row = $stmt->fetch()) 
        {
    		$result = $row['alive_string'];
		}
        return $result;
    }
    public function insertRound($data)
    {
    	$player_id = $_COOKIE['player'];
    	$round_no = $data['round_no'];
    	$farmer = $data['farmer'];
    	$cow1 = $data['cow1'];
    	$cow2 = $data['cow2'];
    	$bunny1 = $data['bunny1'];
    	$bunny2 = $data['bunny2'];
    	$bunny3 = $data['bunny3'];
    	$bunny4 = $data['bunny4'];
        $sql = "INSERT INTO rounds(player_id,round_no,farmer,cow1,cow2,bunny1,bunny2,bunny3,bunny4) VALUES (:player_id,:round_no,:farmer,:cow1,:cow2,:bunny1,:bunny2,:bunny3,:bunny4)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':player_id',$player_id);
        $stmt->bindParam(':round_no',$round_no);
        $stmt->bindParam(':farmer',$farmer);
        $stmt->bindParam(':cow1',$cow1);
        $stmt->bindParam(':cow2',$cow2);
        $stmt->bindParam(':bunny1',$bunny1);
        $stmt->bindParam(':bunny2',$bunny2);
        $stmt->bindParam(':bunny3',$bunny3);
        $stmt->bindParam(':bunny4',$bunny4);
        if($stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function getAllRounds()
    {
        $player_id = $_COOKIE['player'];
        $sql = "select * from rounds where player_id='".$player_id."'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
    public function updateAliveEntities($entity)
    {
    	$player_id = $_COOKIE['player'];
        $base_array = $this->getAliveEntities();
        $base_array = explode(',',$base_array);
        $alive_array = array_diff($base_array, array($entity));
        $alive_entities = implode(',',$alive_array);
        // echo $alive_entities;die();
        $sql = "UPDATE players set alive_string=:alive_entities where player_id=:player_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':alive_entities',$alive_entities);
        $stmt->bindParam(':player_id',$player_id);
        if($stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function deleteRecords()
    {
        $player_id = $_COOKIE['player'];
        $sql = "delete from players where player_id=:player_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':player_id',$player_id);
        if($stmt->execute())
        {
            $sql1 = "delete from rounds where player_id=:player_id";
            $stmt1 = $this->conn->prepare($sql1);
            $stmt1->bindParam(':player_id',$player_id);
            if($stmt1->execute())
            {
                setcookie('player','',time() - 3600);
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}
function genearte_random_string($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, $charactersLength - 1)];
    }
    return $random_string;
}
?>