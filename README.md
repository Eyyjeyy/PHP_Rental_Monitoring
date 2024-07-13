# PHP_Rental_Monitoring
This repository contains the source code for a Rental Monitoring System, designed to streamline administrative tasks related to property management. The system facilitates the management of users, apartments, categories, tenants, and associated paperwork through a web-based interface. 

The project is built using PHP, MySQL for database management, and incorporates essential security measures for data integrity and user privacy.

5/09/24 - Project Start <br>
6/30/24 - Admin Paper Categories - Realtime Data Fetching using SSE <br><br>

7/06/24 - Admin Paper Files <br>
- Added Pagination <br>
- Added Delete Functionality <br>
- Added Download Feature <br>

7/07/24 - Monthly Sending Emails <br>
- Installed PHPMailer <br>
- Added Monthly Email Sending Mechanism (Every 1 Month Sending) <br>
   - Lacks Cron Job (Not Yet Tested on Live Server/Host, Manually trigerred in URL) <br>
   - Admin Class has the sending email function
   - Created sendMonthlyEmails.php. Will serve as the trigger to checking for dates for months passed and if so, will send email <br>
   - If any number of months has passed from date_preferred of tenant but has days in difference example, June 5 and current date is July 9, email won't send <br>
     It has to be exactly months or a month for email to send <br>

7/13/24 - Monthly Sending Emails<br>
- Added Dynamic Price in Email Body, retrieving price based on tenant's house and number of tenants sharing the same house