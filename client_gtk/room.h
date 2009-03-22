#ifndef ROOM_H
#define ROOM_H

#include "def.h"
#include "room.h"
#include "table.h"
#include "chat.h"
#include "player.h"
#include <gtkmm.h>
#include <vector>
#include <string>

class Room
{

public:	
	Room(std::string,std::vector<card_file* >*);
	virtual ~Room();

protected:
	//Signal handlers:
	

public :	
	/* Functions */
	void add_player(Player *);
	void deal_card(Player *);
	
	/* Variables */
	std::string name;
	std::vector<Player *> player_list;
	Table * table;
	Chat * chat;
	Gtk::Frame stat_frame;
	Gtk::VBox panel_vbox;
	Gtk::VBox table_vbox;
	Gtk::HBox input_hbox;
	Gtk::Button send_button;
	Gtk::TextView chat_input;
	Gtk::AspectFrame table_af;

	


};


#endif //ROOM_H