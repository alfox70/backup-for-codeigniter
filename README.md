# backup-for-codeigniter
creates, saves and sends db backup for Codeigniter

Codeigniter library to perform database backups automatically by saving the backup file on the server and / or sending it to the administrator via email.
You can set the number of files to keep on the server (with deletion of the oldest ones) and the frequency of the backup.

Utilization:

1) Create backups/databases folder on root of website and assign permissions to read and write;
2) Create the table backup with the file backup.sql in your db;
3) Copy the file Alphox_bkp.php in libraries folder;
4) Open Alphox_bkp.php and set your email parameter and preferences;
5) you can also set up:

	public $day_bkp = 5;   (days of backup interval; 0 = at each call)
	
	public $n_db = 3;      (max number of files backup to store)

6) Open the home controller (look at the example file home.php) :

				  insert the row $this->load->library('alphox_bkp'); in function __construct()	

				  insert the row $this->alphox_bkp->backup(); in index() function.



if it was useful and you want to offer me a coffee ...
[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif)](https://paypal.me/alfonsoferlita?locale.x=it_IT)
