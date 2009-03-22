#ifndef CHAT_H
#define CHAT_H

#include <gtkmm.h>
#include <vector>
#include <string>

class Chat : public Gtk::TextView
{

public:
	Chat();
	virtual ~Chat();

protected:
	//Signal handlers:
	//virtual void on_switch_page(GtkNotebookPage* page, guint page_num);
	

public :
	void add_text(std::string);


};


#endif //CHAT_H
