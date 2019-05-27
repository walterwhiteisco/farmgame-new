<?php 
include_once('common.php');
include_once('header.php');
include_once('functions.php');
$page = 'home';
$obj = new Project();
if(!isset($_COOKIE['player']))
{
    $result = $obj->insertPlayer();//set sookie for new user
    $all_rounds = 0;
    $game_over_reason = '';
    $disable = '';
}
else
{
    $farmer_diff = 0;
	$cow1_diff = 0;
	$cow2_diff = 0;
	$bunny1_diff = 0;
	$bunny2_diff = 0;
	$bunny3_diff = 0;
	$bunny4_diff = 0;
    $all_rounds = $obj->getAllRounds();//get all rounds data
    $all_rounds_count = count($all_rounds);
    foreach ($all_rounds as $rounds)
    {
        $rounds['farmer'] == '0' ? $farmer_diff++ : $farmer_diff = 0;
        $rounds['cow1'] == '0' ? $cow1_diff++ : $cow1_diff = 0;
        $rounds['cow2'] == '0' ? $cow2_diff++ : $cow2_diff = 0;
        $rounds['bunny1'] == '0' ? $bunny1_diff++ : $bunny1_diff = 0;
        $rounds['bunny2'] == '0' ? $bunny2_diff++ : $bunny2_diff = 0;
        $rounds['bunny3'] == '0' ? $bunny3_diff++ : $bunny3_diff = 0;
        $rounds['bunny4'] == '0' ? $bunny4_diff++ : $bunny4_diff = 0;
    }
    if($farmer_diff >= 15)
    {
        $update_farmer = $obj->updateAliveEntities('1');//remove entity if it is not fed
    }
    if($cow1_diff >= 10)
    {
        $update_cow1 = $obj->updateAliveEntities('2');
    }
    if($cow2_diff >= 10)
    {
        $update_cow2 = $obj->updateAliveEntities('3');
    }
    if($bunny1_diff >= 8)
    {
        $update_bunny1 = $obj->updateAliveEntities('4');
    }
    if($bunny2_diff >= 8)
    {
        $update_bunny2 = $obj->updateAliveEntities('5');
    }
    if($bunny3_diff >= 8)
    {
        $update_bunny3 = $obj->updateAliveEntities('6');
    }
    if($bunny4_diff >= 8)
    {
        $update_bunny4 = $obj->updateAliveEntities('7');
    }
    $game_over = 0;
    $game_over_reason = '';
    if(($all_rounds_count < 50) && ($farmer_diff >= 15))//check if farmer is dead
    {
        $game_over = 1;
        $game_over_reason = 'You lost,Farmer dead';
    }
    else if(($all_rounds_count == 50) && (($farmer_diff < 15) && ($cow1_diff < 10 || $cow2_diff < 10) && ($bunny1_diff < 8 || $bunny2_diff < 8 || $bunny3_diff < 8 || $bunny4_diff < 8)))//check if all conditions satisfy for player to win
    {
        $game_over = 1;
        $game_over_reason = 'You win!!!';
    }
    else if(($all_rounds_count <= 50) && ($farmer_diff < 15) && (($cow1_diff >= 10 && $cow2_diff >= 10) || ($bunny1_diff >= 8 && $bunny2_diff >= 8 && $bunny3_diff >= 8 && $bunny4_diff >= 8)))//check if farmer and atleast one cow and rabbit is alive
    {
        $game_over = 1;
        $game_over_reason = 'You lost, you should have farmer and at least one cow and one bunny alive.';
    }
    else
    {
        $game_over = 0;
        $game_over_reason = '';
    }
    $all_rounds_count == 50 || $game_over == 1 ? $disable = 'disabled' : $disable = '';
    if(($all_rounds_count == 50 || $farmer_diff >= 15) || (($all_rounds_count <= 50) && ($farmer_diff < 15) && (($cow1_diff >= 10 && $cow2_diff >= 10) || ($bunny1_diff >= 8 && $bunny2_diff >= 8 && $bunny3_diff >= 8 && $bunny4_diff >= 8))))//delete records once the game is over
    {
        $delete_records = $obj->deleteRecords();
    }
}

?>
<body>
<div class="container">
    <div class="row">
    	<table class="table table-bordered mt-5 text-center">
    	    <thead>
    	      <tr>
    	        <th>Round</th>
    	        <th class="<?php echo $farmer_diff >= 15 ? 'dead' : 'alive';?>">Farmer</th>
    	        <th class="<?php echo $cow1_diff >= 10 ? 'dead' : 'alive';?>">Cow-1</th>
    	        <th class="<?php echo $cow2_diff >= 10 ? 'dead' : 'alive';?>">Cow-2</th>
    	        <th class="<?php echo $bunny1_diff >= 8 ? 'dead' : 'alive';?>">Bunny-1</th>
    	        <th class="<?php echo $bunny2_diff >= 8 ? 'dead' : 'alive';?>">Bunny-2</th>
    	        <th class="<?php echo $bunny3_diff >= 8 ? 'dead' : 'alive';?>">Bunny-3</th>
    	        <th class="<?php echo $bunny4_diff >= 8 ? 'dead' : 'alive';?>">Bunny-4</th>
    	      </tr>
    	    </thead>
    	    <tbody>
    	    	<?php
                if(isset($_COOKIE['player']))
                {
    	    	    if($all_rounds_count > 0)
    	    	    {
    	    		    foreach ($all_rounds as $row)
    	                {
    	    	?>
    	    		    <tr>
    	    		      <td><?php echo $row['round_no']; ?></td>
    	    		      <td><?php echo $row['farmer'] == '1' ? 'fed' : ''; ?></td>
    	    		      <td><?php echo $row['cow1'] == '1' ? 'fed' : ''; ?></td>
    	    		      <td><?php echo $row['cow2'] == '1' ? 'fed' : ''; ?></td>
    	    		      <td><?php echo $row['bunny1'] == '1' ? 'fed' : ''; ?></td>
    	    		      <td><?php echo $row['bunny2'] == '1' ? 'fed' : ''; ?></td>
    	    		      <td><?php echo $row['bunny3'] == '1' ? 'fed' : ''; ?></td>
    	    		      <td><?php echo $row['bunny4'] == '1' ? 'fed' : ''; ?></td>
    	    		    </tr>
    	    	<?php
    	                }
    	            }
                }
                else
                {
    	    	?>
                <tr></tr>    
                <?php
                }
                ?>
    	    </tbody>
    	</table>
        <div class="col-sm-12 text-center mt-5 mb-5"><button type="button" class="btn btn-primary feedbutton" <?php echo $disable?>>Feed</button></div>
        <input type="hidden" class="game_over_reason" value="<?php echo $game_over_reason;?>">
    </div>
</div>
<?php include_once('footer.php');?>
</body>
</html>