
# Veterinary Clinic Management System 
Capstone 1 & 2 Project
By: Jalen-0924

## Prerequisite

Before you begin, ensure you have the following software installed on your machine:

- **XAMPP**: Version 7.4 or higher (includes Apache, MySQL, PHP). Download it from [Apache Friends](https://www.apachefriends.org/index.html).
- **Composer**: Version 2.0 or higher. You can download it from [getcomposer.org](https://getcomposer.org/download/).
- **PHP**: Version 7.4 or higher (included with XAMPP).
- **A web browser**: For accessing the application locally (latest version recommended).

## Installation

Follow these steps to set up the Veterinary application on your local machine using XAMPP:

1. **Extract Files**  
   Extract the downloaded files and create a new folder. Rename the folder to `veterinary` and paste all the extracted files into this folder.

2. **Move Folder to XAMPP**  
   Copy the `veterinary` folder to the `htdocs` directory in your XAMPP installation. Typically, this would be located at:  
   ```
   C:\xampp\htdocs\
   ```

3. **Import Database**  
   - Start XAMPP and open **phpMyAdmin** by navigating to `http://localhost/phpmyadmin` in your web browser.
   - Click on the **Databases** tab.
   - Create a new database and name it `veterinary`.
   - Select the newly created database from the left sidebar.
   - Click on the **Import** tab.
   - Choose the `veterinary.sql` file that is included with the extracted files and click on **Go** to import the database.

4. **Modify PHP Configuration**  
   - Start XAMPP and click on `php.ini` in the Apache configuration settings.
   - Search for the line that contains `;extension=intl` and remove the semicolon (`;`) at the beginning of the line to enable the Internationalization extension.

5. **Install Composer**  
   Open your command line interface and navigate to the `veterinary` folder to install Composer. You can do this with the following commands:  
   ```bash
   cd C:\Users\User\Desktop\xampp\htdocs\veterinary
   composer install
   ```

6. **Access PHP Info**  
   - Start XAMPP and navigate to your web browser.
   - Access `localhost/` and click on `phpinfo()`.
   - Copy all details displayed on the `phpinfo()` page.

7. **Configure Xdebug**  
   - Go to [Xdebug Wizard](https://xdebug.org/wizard) and paste the copied `phpinfo()` details.
   - Follow the instructions provided on the Xdebug Wizard page to configure Xdebug for your setup.

8. **Access the Application**  
   Once the setup is complete, access the application by visiting:  
   ```
   http://localhost/veterinary
   ```

## Credentials

You can log in with the following credentials:

- **Admin**  
  - Email: `pawsomefuriends.business@gmail.com`  
  - Password: `VetAdmin99`

- **Doctor**  
  - Email: `doctor@gmail.com`  
  - Password: `Doctor01`

- **Client**  
  - Email: `YOUR OWN EMAIL`  
  - Password: `YOUR OWN PASSWORD`

## Extra Libraries

The application utilizes the following libraries:

- **PHPSpreadsheet**: For handling spreadsheet data.
- **PHPMailer**: For sending emails.

Make sure to have these libraries installed via Composer as specified in the installation steps.
