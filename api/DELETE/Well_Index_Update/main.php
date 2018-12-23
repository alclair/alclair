<?php
include_once "../../config.dev.inc.php";

// INSTRUCTIONS
// 1. DOWNLOAD WELL INDEX LIST FROM NDIC
// 2. RE-TITLE AND REARRANGE THE COLUMNS - LAST COLUMN SHOULD BE CURRENT OPERATOR
// 3. REMOVE ALL APOSTROPHES FROM CURRENT OEPRATOR COLUMN
// 4. SAVE *.CSV FILE
// 5. CREATE TABLE INSIDE DATABASE - PROBABLY WILL NEED TO PROVIDE PRIVILEGES TO POSTGRES USER - SQL CODE IS BELOW
// 6. IMPORT *.CSV FILE TO CREATE THE COMPLETE TABLE 

/*
SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

CREATE TABLE well_index (
    id integer DEFAULT nextval('well_index_id_seq'::regclass) NOT NULL,
    file_number integer,
    api_number bigint,
    well_type character varying(15),
    current_operator_id integer,
    current_well_name character varying(80),
    spud_date date,
    td integer,
    county character varying(45),
    township character varying(5),
    rng character varying(5),
    section integer,
    qq character varying(8),
    field_name character varying(45),
    latitude character varying(25),
    longitude character varying(25),
    well_status character varying(25),
    feet_ew integer,
    feet_ns integer,
    fewl character varying(1),
    fnsl character varying(1),
    active smallint,
    date_created timestamp without time zone,
    is_from_ndic smallint,
    validated smallint,
    created_by_id integer,
    current_operator character varying(80)
);


ALTER TABLE well_index OWNER TO caraburo_consult;


CREATE SEQUENCE ticket_tracker_well_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ticket_tracker_well_id_seq OWNER TO caraburo_consult;


ALTER SEQUENCE ticket_tracker_well_id_seq OWNED BY wells_list.id;


ALTER TABLE ONLY wells_list
    ADD CONSTRAINT ticket_tracker_well_pkey PRIMARY KEY (id);


CREATE INDEX ticket_tracker_well_name_idx ON wells_list USING btree (current_well_name);


CREATE INDEX ticket_tracker_well_operator_id_idx ON wells_list USING btree (current_operator_id);


ALTER TABLE ONLY wells_list
    ADD CONSTRAINT ticket_tracker_well_operator_id_fk FOREIGN KEY (current_operator_id) REFERENCES ticket_tracker_operator(id) ON DELETE SET NULL;	
*/

// Report all errors except E_NOTICE
//error_reporting(E_ALL & ~E_NOTincludes/header.inc.phpICE);

//session_start();

$rootScope=array();

$rootScope["RootPath"]="/var/www/html/dev/";

$rootScope["RootUrl"]="http://dev.swdmanager.com";
$rootScope['m_Theme'] = $rootScope['RootUrl'].'/css/metronic_v4.5.0/theme/assets';	
$rootScope["APIUrl"]="/api/";
$rootScope["PageSize"]="100";
$rootScope["SWDCustomer"]="trd";
$rootScope["SWDRootUrl"]="http://dev.swdmanager.com/";
$rootScope["SWDApiToken"]="83167892";

$rootScope["SupportEmail"]="tfolsom@assetvision.com";

$rootScope["SupportName"]="SWD Manager for DEV";

$db_server="127.0.0.1";
$db_database="trd";
$db_user="postgres";
$db_password="Gorilla1";

try
{
        //$pdo_main=new PDO("pgsql:host=$db_server;dbname=$db_database;user=$db_user;password=$db_password");
        
		$query = 'SELECT * FROM well_index WHERE file_number > 30000';
        $stmt = pdo_query( $pdo, $query, null ); 
		$well_index = pdo_fetch_all( $stmt );
		//echo ($well_index[0]["file_number"]);		 
		
		foreach($well_index as $WellIndex) {
			 // FOR LOOP FOR EACH ENTRY IN WELL INDEX	
			 $params[":FileNumber"] = $WellIndex["file_number"]; 
			 $query="SELECT * FROM wells_list WHERE file_number = {$WellIndex["file_number"]}";//:FileNumber";
			 $stmt = pdo_query( $pdo, $query, null ); 
			 $wells_list_FileNumber = pdo_fetch_all( $stmt );
			 if(!empty($wells_list_FileNumber)) { // FILE NUMBER ALREADY EXISTS INSIDE WELLS LIST
			 	// DO NOTHING
			 	//echo "INSIDE FIRST IF STATEMENT";
		 	} else { // WELL DOES NOT ALREADY EXIST IN WELLS LIST AND WILL NEED TO BE ADDED
			 	//echo "INSIDE FIRST ELSE STATEMENT";
			 	$params_oper[":name"] = $WellIndex["current_operator"];  // CURRENT OPERATOR IS A STRING WHICH IS THE NAME OF THE OPERATOR
			 	$query = "SELECT * FROM ticket_tracker_operator WHERE name = '{$WellIndex["current_operator"]}'";//:CurrentOperatorName";
			 	$stmt = pdo_query( $pdo, $query, null); 
			 	$operator = pdo_fetch_array( $stmt );
			 	if(empty($operator)) { // OPERATOR DOES NOT EXIST
					// DELETE FROM ticket_tracker_operator WHERE id > 1850;
				 	// INSERT NEW OPERATOR
				 	$parentid=null;
				 	$query = "INSERT INTO ticket_tracker_operator(name,parentid) VALUES(:name,:parentid) RETURNING id";
				 	$params_operator=array(":name"=>$WellIndex["current_operator"],":parentid"=>$parentid);
				 	$stmt = pdo_query($pdo, $query, $params_operator);
				 	$insert_operator = pdo_fetch_array( $stmt ); 
				 	// SET CURRENT OPERATOR ID
				 	$WellIndex["current_operator_id"] = $insert_operator["id"];
			 	} else { // OPERATOR DOES EXIST
				 	$WellIndex["current_operator_id"] = $operator["id"];
				 	//echo $WellIndex["current_operator_id"];
			 	}
			 	
			 	// INSERT NEW WELL
			 	// DELETE FROM wells_list WHERE id > 33629  //  SELECT * FROM wells_list ORDER BY id
			 	$query = "INSERT INTO wells_list (file_number, api_number, well_type, current_operator_id, current_well_name, spud_date, td, county, township, rng, section, qq, field_name, latitude, longitude, well_status, feet_ew,	feet_ns, fewl, fnsl, active, date_created,	is_from_ndic, validated,	created_by_id)	
			 				VALUES (
			 				:file_number, 
							:api_number, 
							:well_type, 
							:current_operator_id, 
							:current_well_name, 
							:spud_date, 
							:td, :county, 
							:township, 
							:rng, 
							:section, 
							:qq,	
							:field_name, 
							:latitude, 
							:longitude, 
							:well_status, 
							:feet_ew, 
							:feet_ns, 
							:fewl, 
							:fnsl, 
							:active, 
							:date_created, 
							:is_from_ndic, 
							:validated, 
							:created_by_id) RETURNING id";
										 					
				$stmt = pdo_query($pdo, $query, 
				array(
				':file_number'=>$WellIndex['file_number'], 
				':api_number'=>$WellIndex['api_number'], 
				':well_type'=>$WellIndex['well_type'], 
				':current_operator_id'=>$WellIndex['current_operator_id'], 
				':current_well_name'=>$WellIndex['current_well_name'], 
				':spud_date'=>$WellIndex['spud_date'], 
				':td'=>$WellIndex['td'], 
				':county'=>$WellIndex['county'], 
				':township'=>$WellIndex['township'], 
				':rng'=>$WellIndex['rng'], 
				':section'=>$WellIndex['section'], 
				':qq'=>$WellIndex['qq'], 
				':field_name'=>$WellIndex['field_name'], 
				':latitude'=>$WellIndex['latitude'], 
				':longitude'=>$WellIndex['longitude'], 
				':well_status'=>$WellIndex['well_status'], 
				':feet_ew'=>$WellIndex['feet_ew'], 
				':feet_ns'=>$WellIndex['feet_ns'], 
				':fewl'=>$WellIndex['fewl'], 
				':fnsl'=>$WellIndex['fnsl'], 
				':active'=>$WellIndex['active'], 
				':date_created'=>$WellIndex['date_created'], 
				':is_from_ndic'=>$WellIndex['is_from_ndic'], 
				':validated'=>$WellIndex['validated'], 
				':created_by_id'=>$WellIndex['created_by_id']
				));
				$update_wells_list = pdo_fetch_all( $stmt );

		 	} // CLOSE ELSE
		} // CLOSE FOR EACH		

echo "ALL DONE!";
} // CLOSE TRY

catch(PDOException $ex)

{
        echo "Can't connect to the DB server: ".$ex->getMessage();
		echo "DASFASDFSDF";
        die();

}
//require_once "includes/functions.inc.php";
//$query="select * from settings where id=1";
//$stmt=pdo_query($pdo,$query,null);
//$row=pdo_fetch_array($stmt);
//$rootScope["site_name"]=$row["site_name"];
?>
