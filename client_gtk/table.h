#ifndef TABLE_H
#define TABLE_H

#include "def.h"
#include <gtkmm.h>
#include <vector>

class Table : public Gtk::DrawingArea
{
public:
	Table(std::vector<card_file* >*);
	virtual ~Table();	

protected:
	//Override default signal handler:
	virtual bool on_expose_event(GdkEventExpose* event);
	virtual bool on_motion_notify_event(GdkEventMotion* event);
	virtual bool on_button_press_event(GdkEventButton* event);
	virtual bool on_button_release_event(GdkEventButton* event);

public:	
	/* Functions */
	void draw_table();
	void draw_deck();
	void draw_background();
	int on_deck();
	void deal_card(std::vector<int>);
	
	/* Variables */
	struct card{
		card_file * card_f; //pointer to the card_file
		bool pointed; //card being pointed 
		bool moving; //card being dragged or not
		int drag_x,drag_y; //to locate the grab point on the card
		int drag_xb,drag_yb;//to locate the origin of a mouvement
		int value;
	};
	
	std::vector<card* > card_list;
	std::vector<card* >::iterator card_it;
	
	card* card_pointed; //pointer on the card being pointed
	card* card_dragged; //pointer on the card being dragged
	
	struct background{
		Glib::RefPtr<Gdk::Pixbuf> source;
		Glib::RefPtr<Gdk::Pixbuf> scaled;		
	}background;	
	
	Glib::RefPtr<Gdk::Pixmap> back; //graphical buffer
	Glib::RefPtr<Gdk::Pixmap> back_drag; //save of the back at the begining of the drag
	std::vector<card_file* >*  card_file_list; //pointer to the file_list
	
	//resize or not
	int height;
	int width;
	
	bool card_quit; //a card has just been left
	bool card_drag; //a card is being dragged
	bool drag_save; //the back has to be saved at the beggining of a drag
};

#endif // Table_H
