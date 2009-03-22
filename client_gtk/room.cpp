#include "room.h"
#include "table.h"
#include "chat.h"
#include "player.h"
#include <iostream>
#include <string>

Room::Room(std::string name_,std::vector<card_file* >* card_file_list)
:
stat_frame("Statistiques"),
send_button("Envoyer"),
table_af("",
    Gtk::ALIGN_CENTER,
    Gtk::ALIGN_CENTER,
    4./3,
    false)
{
	table = new Table(card_file_list);
	chat = new Chat;
	name = name_;
	std::string wel = "Bienvenue sur la table ";
	wel += name;
	wel+= " !\n";
	chat->add_text(wel);
	stat_frame.set_border_width(5);
	
	
}

Room::~Room()
{
	delete table,chat;
}


void Room::add_player(Player * player)
{
	player_list.push_back(player);	
	std::string wel = "";
	wel += player->name;
	wel+= " a rejoint la table.\n";
	chat->add_text(wel);
}

void Room::deal_card(Player * player)
{
	table->deal_card(player->card_list);	
}