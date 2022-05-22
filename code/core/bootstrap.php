<?php
error_reporting(E_WARNING);

//region --- CORE ---
include($base.'Config.php');
include($base.'code/core/SingletonRegistry.php');
include($base.'code/core/AbstractSingleton.php');
//endregion --- CORE ---


//region --- CONSTANT ---
include($base."code/constant/GameType.php");
include($base."code/constant/PartyStatut.php");
include($base."code/constant/Role.php");
include($base."code/constant/RoleCalculation.php");
include($base."code/constant/Roles.php");
include($base."code/constant/RoleCategorie.php");

include($base."code/constant/roles/Gay.php");
include($base."code/constant/roles/Krik.php");
include($base."code/constant/roles/Ratio.php");
include($base."code/constant/roles/Sasuke.php");
include($base."code/constant/roles/SussyBaka.php");
//endregion --- CONSTANT ---

//region --- DTO ---
include($base.'code/dto/AbstractDTO.php');
include($base.'code/dto/AbstractIdentifierDTO.php');

include($base."code/dto/PartyDTO.php");
include($base."code/dto/GameDTO.php");
include($base."code/dto/SessionDTO.php");
include($base."code/dto/GameSessionDTO.php");
include($base."code/dto/EndVoteDTO.php");
include($base."code/dto/EndStatDTO.php");

include($base."code/dto/partyAPI/PartyWorkflowDTO.php");
include($base."code/dto/partyAPI/PartyLobby.php");
include($base."code/dto/partyAPI/GameInGameDTO.php");
include($base."code/dto/partyAPI/GameEndStatDTO.php");
include($base."code/dto/partyAPI/GameVotingDTO.php");
include($base."code/dto/partyAPI/GameVotedDTO.php");
include($base."code/dto/partyAPI/GameEndGameDTO.php");
//endregion --- DTO ---

//region --- DAO ---
include($base.'code/dao/AbstractDAO.php');
include($base.'code/dao/AbstractIdentifierDAO.php');

include($base.'code/dao/PartyDAO.php');
include($base."code/dao/GameDAO.php");
include($base."code/dao/SessionDAO.php");
include($base."code/dao/GameSessionDAO.php");
include($base."code/dao/EndVoteDAO.php");
include($base."code/dao/EndStatDAO.php");
//endregion --- DAO ---

//region --- SERVICE ---
include($base."code/service/AbstractIdentifierService.php");

include($base."code/service/PartyService.php");
include($base."code/service/SessionService.php");
include($base."code/service/GameService.php");
include($base."code/service/GameSessionService.php");
include($base."code/service/EndStatService.php");
include($base."code/service/EndVoteService.php");

include($base."code/service/SessionManager.php");
//endregion --- SERVICE ---


include($base."code/controller/AbstractController.php");

include($base.'code/core/Request.php');
include($base."code/core/Router.php");


SingletonRegistry::init();


