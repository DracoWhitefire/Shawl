<?php
// New Agent Form
	if($editAgent == TRUE) {
		if(isset($_POST["singleEditList"])) {
			$agent_set = get_agents($_POST["singleEditList"]);
		} elseif(isset($_POST["agentId_input"])) {
			$agent_set = get_agents($_POST["agentId_input"]);
		}
		$agent_array = mysqli_fetch_array($agent_set);
	}
	
	$agentForm = "<div id=\"agentForm_div\"><form id=\"agentForm_form\" method=\"POST\" action=\"index.php" . htmlspecialchars("?id={$current_id}") . "\">";
	if($editAgent == TRUE) {
		$agentForm .= "<input type=\"hidden\" name=\"agentId_input\" value=\"" . htmlspecialchars($agent_array['id']) . "\" />";
	}
	$agentForm .= "<div id=\"agentFormPersonal_div\"><label for=\"userName_input\">CTM Username</label><input type=\"text\" id=\"userName_input\" name=\"userName_input\" ";
	if(isset($errors["userName_input"])) {
		$agentForm .= "class=\"error\" ";
	}
	if($editAgent == TRUE) {
		$agentForm .= "value=\"";
		if(isset($_POST["userName_input"])) {
			$agentForm .= htmlspecialchars($_POST["userName_input"]);
		} else {
			$agentForm .=  htmlspecialchars($agent_array['user_name']);	
		}
		$agentForm .= "\" ";
	}
	$agentForm .= "/><br />";
	$agentForm .= "<label for=\"forumName_input\">Forum Name</label><input type=\"text\" id=\"forumName_input\" name=\"forumName_input\" autocomplete=\"off\" ";
	if(isset($errors["forumName_input"])) {
		$agentForm .= "class=\"error\" ";
	}
	if($editAgent == TRUE) {
		$agentForm .= "value=\"";
		if(isset($_POST["forumName_input"])) {
			$agentForm .= htmlspecialchars($_POST["forumName_input"]);
		} else {
			$agentForm .=  htmlspecialchars($agent_array['forum_name']);	
		}
		$agentForm .= "\" ";
	}
	$agentForm .= "/><br />";
	$agentForm .= "<label for=\"firstName_input\">First Name</label><input type=\"text\" id=\"firstName_input\" name=\"firstName_input\" autocomplete=\"off\" ";
	if(isset($errors["firstName_input"])) {
		$agentForm .= "class=\"error\" ";
	}
	if($editAgent == TRUE) {
		$agentForm .= "value=\"";
		if(isset($_POST["firstName_input"])) {
			$agentForm .= htmlspecialchars($_POST["firstName_input"]);
		} else {
			$agentForm .=  htmlspecialchars($agent_array['first_name']);	
		}
		$agentForm .= "\" ";
	}
	$agentForm .= "/><br />";
	$agentForm .= "<label for=\"lastName_input\">Last Name</label><input type=\"text\" id=\"lastName_input\" name=\"lastName_input\" autocomplete=\"off\" ";
	if(isset($errors["lastName_input"])) {
		$agentForm .= "class=\"error\" ";
	}
	if($editAgent == TRUE) {
		$agentForm .= "value=\"";
		if(isset($_POST["lastName_input"])) {
			$agentForm .= htmlspecialchars($_POST["lastName_input"]);
		} else {
			$agentForm .=  htmlspecialchars($agent_array["last_name"]);	
		}
		$agentForm .= "\" ";
	}
	$agentForm .= "/><br />";
	$rank_array = array("Guest" => 1, "Agent" => 10, "Admin" => 50, "Superadmin" => 100);
	$agentForm .= "<label for=\"rank_select\">Rank</label><select id=\"rank_select\" name=\"rank_select\">";
	foreach($rank_array as $rankName => $rankValue) {
		$agentForm .= "<option value=\"" . htmlspecialchars($rankValue) . "\" ";
		if($editAgent == TRUE) {
			if(isset($_POST["rank_select"])) {
				if($_POST["rank_select"] == $rankValue) {
					$agentForm .= "selected=\"selected\" ";
				}
			} else {
				if($agent_array["rank"] == $rankValue) {
					$agentForm .= "selected=\"selected\" ";
				}
			}
		}
		$agentForm .= ">" . htmlspecialchars($rankName) . "</option>";
	}
	$agentForm .= "</select><br />";
	$agentForm .= "<label for=\"active_input\" class=\"check\">Active</label><input type=\"checkbox\" id=\"active_input\" name=\"active_input\" ";
	if($editAgent == TRUE) {
		if(isset($_POST["active_input"])) {
			if($_POST["active_input"] == TRUE) {
				$agentForm .=  "checked=\"checked\" ";	
			}
		}
		if($agent_array["active"] == TRUE) {
			$agentForm .=  "checked=\"checked\" ";	
		}
	}
	$agentForm .= "/><br />";
	$agentForm .= "<label for=\"password_input\">Password</label><input type=\"password\" id=\"password_input\" name=\"password_input\" ";
	if(isset($errors["password_input"])) {
		$agentForm .= "class=\"error\" ";
	}
	$agentForm .= "/><br />";
	$agentForm .= "<label for=\"confPassword_input\">Confirm Password</label><input type=\"password\" id=\"confPassword_input\" name=\"confPassword_input\"  ";
	if(isset($errors["confPassword_input"])) {
		$agentForm .= "class=\"error\" ";
	}
	$agentForm .= "/><br />";
	$agentForm .= "<label for=\"changePw_input\" class=\"check\">Change password on next login</label><input type=\"checkbox\" id=\"changePw_input\" name=\"changePw_input\" /></div><hr />";
	$weekdays_array = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday");
	$agentForm .= "<div id=\"agentFormSchedule_div\"><table id=\"agentFormSchedule_table\">";
	$agentForm .= "<thead><tr><th>Weekday</th><th>Begin Shift</th><th>End Shift</th></tr></thead><tbody>";
	foreach($weekdays_array as $weekday) {
		if($editAgent == TRUE) {
			$agentSched_array = get_sch_for_agent($agent_array["id"], strtolower($weekday));
		}
		$agentForm .= "<tr><td>{$weekday}</td><td><input type=\"text\" name=\"{$weekday}Begin_input\" ";
		if(isset($errors["{$weekday}Begin_input"])) {
			$agentForm .= "class=\"error\" ";
		}
		if($editAgent == TRUE) {
			if(isset($_POST["{$weekday}Begin_input"])) {
				$agentForm .= "value=\"" . format_time($_POST["{$weekday}Begin_input"], "html") . "\" ";
			}
			$agentForm .= "value=\"" . format_time($agentSched_array["start_time"], "html") . "\" ";
		}
		$agentForm .= "/></td><td><input type=\"text\" name=\"{$weekday}End_input\" ";
		if(isset($errors["{$weekday}End_input"])) {
			$agentForm .= "class=\"error\" ";
		}
		if($editAgent == TRUE) {
			if(isset($_POST["{$weekday}End_input"])) {
				$agentForm .= "value=\"" . format_time($_POST["{$weekday}End_input"], "html") . "\" ";
			}
			$agentForm .= "value=\"" . format_time($agentSched_array["end_time"], "html") . "\" ";
		}
		$agentForm .= "/></td></tr>";
	}
	$agentForm .= "</tbody></table></div><hr />";
	if($addAgent == TRUE) {
		$agentForm .= "<input type=\"submit\" value=\"Add User\" name=\"submitForm\" />";
	} elseif($editAgent == TRUE) {
		$agentForm .= "<input type=\"submit\" value=\"Submit User\" name=\"submitForm\" />";
	}	
	$agentForm .= "<input type=\"reset\" value=\"Reset\" />";
	$agentForm .= "<input type=\"submit\" value=\"Cancel\" name=\"cancelSubmitForm\" />";
	$agentForm .= "</form></div>";
	
	mysqli_free_result($agent_set);
?>