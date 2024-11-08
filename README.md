# PHP_Rental_Monitoring
This repository contains the source code for a Rental Monitoring System, designed to streamline administrative tasks related to property management. The system facilitates the management of users, apartments, categories, tenants, and associated paperwork through a web-based interface. 

The project is built using PHP, MySQL for database management, and incorporates essential security measures for data integrity and user privacy.

🖥 <strong>Languages:</strong> PHP, Javascript, HTML, CSS <br>
📚 <strong>Libraries:</strong> Chart.js, JQuery, PHPMailer <br>
☁  <strong>API:</strong> Semaphore SMS <br>
🖼 <strong>Frameworks:</strong> Bootstrap 5 <br>
🔨 <strong>Tools:</strong> phpMyAdmin, Cron Job <br>
👨‍💻 <strong>Data Formats:</strong> JSON <br>



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
- Added Dynamic Price in Email Body, retrieving price based on tenant's house and number of tenants sharing the same house <br>

7/15/24 - Revisions <br>
- Revised Apartment addition and update <br>
  - House Number to House Name <br>
  - Category ID to Category Name <br>
    - Shown Option in dropdown from category's ID to name (Value input is still category's ID) <br>

7/16/24 - Added Meralco Account Autofill <br>
- Updated SQL query to join houses with houseaccounts on houses.id = houseaccounts.houses_id <br>
- Modified house_data generation to include data-meralconum attribute for the update button <br>
- Adjusted JavaScript to autofill the Meralco Account field in the update modal <br>

7/16/24 - Revised Adminhouses Forms <br>
- Revised New Modal Fields to be side by side <br>
- Revised Update Modal Fields to be side by side <br>

7/22/24 - Created Adminpayments <br>
- Admin can approve or decline payment/s of tenants <br>
- Approved payments are taken into payments.php's calculation mechanism <br>
- Added Image Preview on click <br>

7/24/24 - User Payments <br>
- Added Monthly Balance which resets to 0 when covered by payment, only shows price to pay for the month and not total <br>

7/26/24 - User Chat <br>
- Added Chat for User Side, different UI from admin chat <br>
- Implemented SSE for realtime update from database operations, such as receiving and display of message from other user while on chat page <br>

7/31/24 - Automated MySQL DB Setup <br>
- Created db_setup.php script responsible for checking and automated setup of MySQL database, requires .sql file <br>
- Modified admin.php, inserted code in constructor integrating db_setup.php <br>

8/2/24 - User Side Navbar Revision & Footer <br>
- Added History for Admin Activities <br>
- Applied same navbar from index.php for all non-admin pages for uniformity <br>

8/5/24 - Added History Page <br>
- Utilizes History function from admin.php <br>

8/9/24 - History Functionality <br>
- Added History: Delete user, Update user, Add User <br>
- Added History for Delete House/Apartments <br>

8/10/24 - History Functionality <br>
- Added History: Update House <br>

8/11/24 - History Functionality <br>
- Continuation until tenants <br>

8/12/24 - History Functionality <br>
- Completion of History for administrator actions <br>

8/12/24 - Added Expenses Page <br>
- Created expenses table for phpMyAdmin (MYSQL) <br>
- Completed, Add, Update and Delete for Expenses <br>

8/15/24 - Added Income Expenses Comparison Chart to Dashboard <br>
- Created function getIncomeExpensesData for retrieval of Data from MYSQL in admin.php <br>

8/17/24 - Added Profile Page Backend <br>
- Created function updateUserProfile, getUserProfile, updateTenantProfile, updatePaymentsTable <br>
- Utilizes History Function from admin.php <br>

8/18/24 - Added Info Page for Users <br>
- Info Page retrieves logged in user's account from houseaccounts table. <br>

9/21/24 - Added Info Page for Users <br>
- Merged table from admincategories.php to adminhouses.php. <br>
- Improved admin/includes/header.php code indentations <br>

9/23/24 - Integrated Semaphore curl API, Revised format of email contents, changed sender gmail <br>

9/26/24 - Admin.php <br>
- Revised addPayment function to include name validation of the user due to tester's feedback being able to commit client-side manipulation through editing of value by inspect element before submitting user payment data <br> 

10/4/2024 - Created "Seen" feature for admin and user chats <br>
- Created mark_seen.php that updates message seen column when called by chat or chat_user.php <br>
- Revised chat.php to mark recipient's message as seen. Admin's message will also have its own seen stamp when recipient/regular user sees or fetches the conversation <br>
- Revised chat_user.php to mark recipient's message as seen. Regular user's message will also have its own seen stamp when recipient/admin sees or fetches the conversation <br>

10/6/2024 - Revised text formatting of sms <br>
- Revised admin.php's sendMonthlyPaymentNotifications function to pass tenant and user db info to sendMonthlyEmails.php for sms usage <br>
- Revised sendMonthlyEmails.php to iterate over each tenant for dynamic sending of sms to any number of tenants depending on how many met the condition in sendMonthlyPaymentNotifications <br>

10/25/2024 - adminhouses.php <br>
- Revised html and javascript for sorting data when column header clicked <br>

10/25/2024 - minor frontend content revisions <br>

10/26/2024 - admin and user chats updated to be able to send photos without text message <br>

10/27/2024 - login.php, admin.php <br>
- new column in users table <br>
- admin.php sendotp function sending to user's gmail and cellphone <br>
- login.php now has recovery method to change to new password if user forgot account through OTP method <br>

10/28/2024 - admin.php revised getyearlyincome for dashboard <br>
- revised data shown on chart to only show data/payments that are approved by admin in the income per year chart <br>
- revised "Send OTP" button color from blue to green <br>

11/3/2024 - all user and admin pages revised to include indicator of unseen chat messages in navbar and header <br>
- unable to use more than one sse on each page and had to use polling <br>
- created fetch_unread_count and fetch_unseen_count_specific for counting of total unread messages for all users and total unread messages per user <br>

11/7/2024
- Removed contact from input of adding new tenants in admintenants <br>
- added gcash and bank columns for houseaccounts <br>
- added realtime search bar feature for many admin pages with tables <br>

11/8/2024
- Added pagination for users, apartments, tenants, payments (pages) <br>
- Revised search_page files to integrate pagination in order for pagination, search, and sorting to be able to work together <br>