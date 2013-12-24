
all:
	php -S localhost:8000

site:
	cp br7portal.conf /etc/apache2/sites-available/
	-a2dissite br7portal.conf
	-a2ensite br7portal.conf
	service apache2 reload
