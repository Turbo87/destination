# Destination

Destination is a decentralised gliding contest based on the data of the
[OLC](http://www.onlinecontest.org/) server, but specifically tailored to the
clubs on a certain airfield.

The first implementation was called "Destination Meiersberg", for the clubs at
the airfield of Meiersberg, near Düsseldorf in Germany.

The purpose of the contest is to increase the motivation to do cross-country
soaring, by introducing a pilot factor that benefits student pilots and pilots
with less experience.

The page updates itself automatically every 30 minutes and scores the flights
according to the contest rules. The score for one flight is calculated by the
following formula:

               (distance * pilot factor * airfield factor)
    score  =  ---------------------------------------------
                            aircraft factor

    aircraft factor = (DAeC handicap / 100)^2
    airfield factor = 1.0 (at the own airfield)
                      0.8 (anywhere else)

The pilot factor is based on the largest flown distances of the individual
pilots. If the flight is done in a double seater the factor of the better
pilot is used for the flight.

    largest distance < 50km:   factor 4.0
    largest distance < 100km:  factor 3.0
    largest distance < 300km:  factor 2.0
    largest distance < 500km:  factor 1.6
    largest distance < 700km:  factor 1.4
    largest distance < 1000km: factor 1.2
    largest distance > 1000km: factor 1.0

The current factor can be viewed from the table under the menu item "Pilots".
For the reasons of fairness we would like all pilots to come forward if there
are wrong values in the tables anywhere so that we can fix those.

## License

Destination is licensed under the GNU General Public License. See the COPYING
file for a copy of the license agreements.
