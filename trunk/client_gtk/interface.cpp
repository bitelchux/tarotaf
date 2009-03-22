#include "interface.h"
#include "room.h"
#include <iostream>
#include <string>

Interface::Interface()
{
	
	load_card();
	
	// Sets the border width of the window.
	set_border_width(10);	
	

	chat_notebook.signal_switch_page().connect(sigc::mem_fun(*this,
	      &Interface::on_switch_page));
	

	// This packs the button into the Window (a container).
	add(back_hbox);	
	//table_af.add(room_notebook);
	//back_hbox.pack_start(table_af);
	back_hbox.pack_start(room_notebook);
	back_hbox.pack_start(chat_notebook);
	
	/* Test */
	
	room_list.push_back(new Room(std::string("Poker"),&card_file_list));
	room_list.push_back(new Room(std::string("Belote"),&card_file_list));
	room_list.push_back(new Room(std::string("Tarot Africain"),&card_file_list));
	
	room_list[0]->add_player(new Player("Elie"));
	room_list[0]->add_player(new Player("Marcus"));
	room_list[0]->add_player(new Player("Jean-Paul"));
	room_list[0]->add_player(new Player("Sheldon"));
	
	room_list[0]->deal_card(room_list[0]->player_list[0]);
			
	/* End test*/	

	//table_af.set_shadow_type(Gtk::SHADOW_NONE);
	room_notebook.set_show_border(false);
	room_notebook.set_show_tabs(false);
	
	//Show pages
	for(room_it=room_list.begin();room_it!=room_list.end();room_it++)
	{
		(*room_it)->table_af.set_shadow_type(Gtk::SHADOW_NONE);
		(*room_it)->table_af.add((*(*room_it)->table));
		(*room_it)->table_vbox.pack_start((*room_it)->table_af);	
		(*room_it)->input_hbox.pack_start((*room_it)->chat_input);	
		(*room_it)->input_hbox.pack_start((*room_it)->send_button);
		(*room_it)->table_vbox.pack_start((*room_it)->input_hbox);		
		room_notebook.append_page((*room_it)->table_vbox,"");	
		(*room_it)->panel_vbox.pack_start((*room_it)->stat_frame);	
		(*room_it)->panel_vbox.pack_start((*(*room_it)->chat));			
		chat_notebook.append_page((*room_it)->panel_vbox,(*room_it)->name);
		
	}

	// The final step is to display this newly created widget...
	show_all_children();
	
}

Interface::~Interface()
{
}

void Interface::on_switch_page(GtkNotebookPage* page, guint page_num)
{
  std::cout << "Switch vers page " <<page_num<< std::endl;
}


void Interface::load_card()
{
	for (int i=0;i<52;i++)
	{		
		card_file* card_t = new card_file;
		std::stringstream ostr;	
		std::string file;		
		ostr << i;		
		file += "img/";file += ostr.str();file += ".png";
		card_t->source=Gdk::Pixbuf::create_from_file(file);		
		card_file_list.push_back(card_t);		
	}	
}


