<?php
  namespace App\Helpers\PBOMission\test;

  require_once("..\PBOMission.php");
  require_once("..\PBOFile\PBOFile.php");
  require_once("..\Mission\Mission.php");
  require_once("..\Mission\SQMConfig.php");
  require_once("..\Mission\MissionElements\MissionGroup.php");
  require_once("..\Mission\MissionElements\MissionLogic.php");
  require_once("..\Mission\MissionElements\MissionMarker.php");
  require_once("..\Mission\MissionElements\MissionObject.php");
  require_once("..\Mission\MissionElements\MissionUnit.php");

  use App\Helpers\PBOMission\PBOMission;
  use App\Helpers\PBOMission\PBOFile\PBOFile;

  $pboMission = new PBOMission('ARC_COOP_AirfieldDay_McPe_New.Bootcamp_ACR.pbo');
  $summary = $pboMission->export();

  echo json_encode($summary, JSON_PRETTY_PRINT);
?>
