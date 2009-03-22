#include <gtkmm.h>
#include "interface.h"
#include "main.h"

int main (int argc, char *argv[])
{
	Gtk::Main app(argc, argv);

	Interface interface;
	
	Gdk::Geometry hints;
	
	interface.set_size_request(800,600);
	
	//~ hints.min_aspect   = 4./3;
	//~ hints.min_aspect = 4./3;
	
	//~ interface.set_geometry_hints(interface,hints,
                           //~ Gdk::HINT_ASPECT);
	
	Gtk::Main::run(interface);

	return 0;
}

