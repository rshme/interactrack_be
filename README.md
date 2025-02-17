InteracTrack Installation Guide
===============================

Follow the steps below to get **InteracTrack** running on your local PC:

1\. Clone the Repository
------------------------

First, clone the repository using Git. Open your terminal or command prompt and run the following command:

    git clone https://github.com/rshme/interactrack_be.git

This will clone the **InteracTrack** project to your local machine.

2\. Configure the .env File
---------------------------

After cloning the repository, navigate to the project folder:

    cd InteracTrack

Next, copy the .env.example file to .env:

    cp .env.example .env

Open the .env file in your text editor and configure the necessary settings, such as database connection, application key, and other environment variables. Make sure you have set the correct database credentials (DB\_HOST, DB\_PORT, DB\_DATABASE, DB\_USERNAME, DB\_PASSWORD).

3\. Run Migrate & Seed
----------------------

Run the following command to migrate the database and seed it with necessary data:

    php artisan migrate --seed

This command will create the necessary tables in your database and populate them with seed data.

4\. Run the Development Server
------------------------------

Finally, start the development server by running the following command:

    php artisan serve

This will start the server, and you can access the **InteracTrack** application at `http://localhost:8000` in your web browser.

Conclusion
----------

You have successfully set up **InteracTrack** on your local machine. Now, please setup up the Frontend side for this project at https://github.com/rshme/interactrack_fe