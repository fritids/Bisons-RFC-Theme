<?php
function add_player_management()
{ add_dashboard_page( 'Manage Players', 'Manage Players', 'manage_players', 'manage-players', 'manage_players_callback'); }
add_action('admin_menu', 'add_player_management');

function manage_players_callback()
{
	echo "<h1>Testing</h1>';
}