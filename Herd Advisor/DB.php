<?php

function dajRezultate($connection, $sql)
{
	$result = $connection->query($sql);
	if (!$result) {
		$error = $connection->errorInfo();
		print "SQL greška: " . $error[2];
		exit();
	}
	else
		return $result;
}

function upisi($connection, $sql)
{
	try
	{
		$count = $connection->exec($sql);
		return $count;
	}
	catch(PDOException $e)
    {
    	$greska = $sql . "<br>" . $e->getMessage();
    	echo $greska;
    	return 0;
    }
}

function izbrisi($conection, $sql)
{
	try
	{
		$conection->exec($sql);
	    return true;
    }
	catch(PDOException $e)
    {
    	echo $sql . "<br>" . $e->getMessage();
    	return false;
    }
}

function promjeni($connection, $sql)
{
	try
	{
		$connection->exec($sql);
	    return true;
    }
	catch(PDOException $e)
    {
    	echo $sql . "<br>" . $e->getMessage();
    	return false;
    }
}

?>