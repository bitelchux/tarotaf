#ifndef DEF_H
#define DEF_H

#include <gtkmm.h>
#include <vector>
#include <string>

struct card_file{
	Glib::RefPtr<Gdk::Pixbuf> source;
	Glib::RefPtr<Gdk::Pixbuf> scaled;
};


#endif //DEF_H