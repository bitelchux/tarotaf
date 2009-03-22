#ifndef INTERFACE_H
#define INTERFACE_H

#include "def.h"
#include <gtkmm.h>
#include "table.h"
#include "chat.h"
#include "room.h"
#include <vector>

class Interface : public Gtk::Window
{

public:
	Interface();
	virtual ~Interface();

protected:
	//Signal handlers:
	virtual void on_switch_page(GtkNotebookPage* page, guint page_num);


	//Member widgets:
	Gtk::HBox back_hbox;
	Gtk::Notebook room_notebook,chat_notebook;

public :
	
	void load_card();
	
	std::vector<Room *> room_list;
	std::vector<Room *>::iterator room_it;	

	std::vector<card_file* > card_file_list;
};


#endif // INTERFACE_H
