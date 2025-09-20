



CREATE TABLE admins (
    AdminID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(50) NOT NULL,
    PasswordHash VARCHAR(255) NOT NULL, 
    FullName VARCHAR(255) NOT NULL,
    Email VARCHAR(255),
    Phone VARCHAR(20),
    Status VARCHAR(50), -- Admin account status (e.g., Active, Inactive)
    LastLogin DATETIME,
    CreatedDate DATETIME,
    UpdatedDate DATETIME,
    ProfilePictureURL VARCHAR(255) 
);




CREATE TABLE areas (
    AreaID INT PRIMARY KEY AUTO_INCREMENT,
    AreaName VARCHAR(255) NOT NULL,
    Latitude DECIMAL(10, 6),
    Longitude DECIMAL(10, 6),
    Description TEXT
);




CREATE TABLE subareas (
    SubAreaID INT PRIMARY KEY AUTO_INCREMENT,
    SubAreaName VARCHAR(255) NOT NULL,
    AreaID INT,
    Latitude DECIMAL(10, 6),
    Longitude DECIMAL(10, 6),
    Description TEXT,
    FOREIGN KEY (AreaID) REFERENCES Areas(AreaID)
);

CREATE TABLE plans (
    PlanID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(255) NOT NULL,
    Volume VARCHAR(255) NOT NULL,
    Price INT(255) NOT NULL
);







CREATE TABLE clients (
    ClientID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    PrimaryEmail VARCHAR(255),
    SecondaryEmail VARCHAR(255),
    Address VARCHAR(255),
    City VARCHAR(255),
    Country VARCHAR(255),
    Zipcode INT(255),
    PrimaryNumber VARCHAR(20),
    SecondaryNumber VARCHAR(20),
    PasswordHash VARCHAR(255), 
    AreaID INT,
    SubAreaID INT,
    PlanID INT,
    Latitude DECIMAL(10, 6),
    Longitude DECIMAL(10, 6),
    LastLogin DATETIME,
    LastPayment DATETIME,
    CreatedDate DATETIME,
    PreferedPaymentMethod INT(255) NOT NULL DEFAULT '3',
    ProfilePictureURL VARCHAR(255), 
    ActiveStatus TINYINT(1), 
    ExpireDate DATETIME,
    FOREIGN KEY (AreaID) REFERENCES areas(AreaID),
    FOREIGN KEY (SubAreaID) REFERENCES subareas(SubAreaID),
    FOREIGN KEY (PlanID) REFERENCES plans(PlanID)
);


CREATE TABLE paymentoptions (
    PaymentOptionID INT PRIMARY KEY AUTO_INCREMENT,
    PaymentOptionImg VARCHAR(50) NOT NULL,
    PaymentOptionName VARCHAR(50) NOT NULL,
    PaymentOptionQuote VARCHAR(50) NOT NULL
);



CREATE TABLE products (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    ProductName VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    Description TEXT
);




CREATE TABLE expensetypes (
    ExpenseTypeID INT PRIMARY KEY AUTO_INCREMENT,
    ExpenseTypeName VARCHAR(50) NOT NULL,
    Description TEXT
);



CREATE TABLE companysettings (
    SettingID INT PRIMARY KEY AUTO_INCREMENT,
    CompanyName VARCHAR(255),
    Motto TEXT,
    Email VARCHAR(255),
    PhoneNumber VARCHAR(20),
    Country VARCHAR(100),
    Address TEXT,
    Website VARCHAR(255),
    Zipcode VARCHAR(20), 
    City VARCHAR(100), 
    TimeZone VARCHAR(50),
    CurrencyName VARCHAR(100),
    CurrencySymbol VARCHAR(100),
    CurrencyCode VARCHAR(100),
    PhoneCode VARCHAR(100),
    LogoURL VARCHAR(255)
);



CREATE TABLE sales (
    SaleID INT PRIMARY KEY AUTO_INCREMENT,
    SaleDate DATE NOT NULL,
    AdminID INT NOT NULL, -- Salesperson who is an admin
    ClientID INT NOT NULL,
    ProductID INT NOT NULL,
    InvoiceNumber VARCHAR(50) NOT NULL,
    Quantity INT NOT NULL,
    UnitPrice DECIMAL(10, 2) NOT NULL,
    Total DECIMAL(10, 2) NOT NULL,
    PaymentOptionID INT,
    Tax INT NOT NULL,
    TaxSymbol VARCHAR(50) NOT NULL,
    PaymentStatus VARCHAR(50),
    UpdatedDate DATETIME,
    FOREIGN KEY (AdminID) REFERENCES admins(AdminID),
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID),
    FOREIGN KEY (ProductID) REFERENCES products(ProductID),
    FOREIGN KEY (PaymentOptionID) REFERENCES paymentoptions(PaymentOptionID)
);






CREATE TABLE payments (
    PaymentID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT,
    PlanID INT,
    InvoiceNumber VARCHAR(50) NULL,
    PaymentAmount DECIMAL(10, 2) NOT NULL,
    PaymentStatus VARCHAR(50),
    PaymentDate DATETIME,
    PaymentOptionID INT,
    InstallationFees INT,
    FOREIGN KEY (ClientID) REFERENCES Clients(ClientID),
    FOREIGN KEY (PlanID) REFERENCES plans(PlanID),
    FOREIGN KEY (PaymentOptionID) REFERENCES paymentoptions(PaymentOptionID)
);



CREATE TABLE advancepayments (
    AdvancePaymentID INT PRIMARY KEY AUTO_INCREMENT,
    PaymentDate DATE NOT NULL,
    FromDate DATE NOT NULL,
    ToDate DATE NOT NULL,
    ClientID INT,
    PaymentOptionID INT,
    PlanID INT,
    AmountPaid DECIMAL(10, 2) NOT NULL,
    CreatedDate DATETIME,
    UpdatedDate DATETIME,
    FOREIGN KEY (ClientID) REFERENCES Clients(ClientID),
    FOREIGN KEY (PaymentOptionID) REFERENCES paymentoptions(PaymentOptionID),
    FOREIGN KEY (PlanID) REFERENCES plans(PlanID)
);


CREATE TABLE stripepayments (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Session_id VARCHAR(255) NOT NULL,
    PaymentID VARCHAR(255) NOT NULL,
    PaidAmount DECIMAL(10, 2) NOT NULL,
    PaidCurrency VARCHAR(255) NULL,
    Payment_status VARCHAR(255) NOT NULL,
    ClientID INT,
    PlanID INT,
    Customer_name VARCHAR(255) NULL,
    Customer_email VARCHAR(255) NULL,
    CreatedDate VARCHAR(255) NULL,
    UpdatedDate VARCHAR(255) NULL,
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID),
    FOREIGN KEY (PlanID) REFERENCES plans(PlanID)
);



CREATE TABLE paypalpayments (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Order_id VARCHAR(255) NOT NULL,
    PaymentID VARCHAR(255) NOT NULL,
    PaidAmount DECIMAL(10, 2) NOT NULL,
    PaidCurrency VARCHAR(255) NULL,
    Payment_status VARCHAR(255) NOT NULL,
    ClientID INT,
    PlanID INT,
    Customer_name VARCHAR(255) NULL,
    Customer_email VARCHAR(255) NULL,
    CreatedDate VARCHAR(255) NULL,
    UpdatedDate VARCHAR(255) NULL,
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID),
    FOREIGN KEY (PlanID) REFERENCES plans(PlanID)
);




CREATE TABLE messages (
    MessageID INT PRIMARY KEY AUTO_INCREMENT,
    SenderName VARCHAR(50) NOT NULL,
    RecipientID INT NOT NULL,
    MessageType VARCHAR(50) NOT NULL,
    MessageContent TEXT NOT NULL,
    Timestamp DATETIME NOT NULL,
    Status VARCHAR(50) NOT NULL,
    FOREIGN KEY (RecipientID) REFERENCES clients(ClientID)
);



CREATE TABLE invoices (
    InvoiceID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT,
    InvoiceNumber VARCHAR(50),
    TotalAmount DECIMAL(10, 2) NOT NULL,
    paymentDate DATE,
    StartDate DATE,
    DueDate DATE,
    Status VARCHAR(50),
    TaxSymbol VARCHAR(50),
    Taxamount INT,
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID)
);



CREATE TABLE invoiceproducts (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    InvoiceID INT,
    Name VARCHAR(255),
    Volume VARCHAR(50),
    Qty INT,
    Price DECIMAL(10, 2) NULL,
    Amount DECIMAL(10, 2) NULL,
    subTotal DECIMAL(10, 2) NULL,
    FOREIGN KEY (InvoiceID) REFERENCES invoices(InvoiceID)
);



CREATE TABLE plan_change_schedule (
    ScheduleID INT AUTO_INCREMENT PRIMARY KEY,
    ClientID INT NOT NULL,
    NewPlanID INT NOT NULL,
    ScheduledDate DATETIME,
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID),
    FOREIGN KEY (NewPlanID) REFERENCES plans(PlanID)
);



CREATE TABLE emails (
    EmailID INT PRIMARY KEY AUTO_INCREMENT,
    SenderID INT,
    RecipientID INT,
    Subject VARCHAR(255),
    EmailContent TEXT,
    Timestamp DATETIME,
    FOREIGN KEY (SenderID) REFERENCES clients(ClientID),
    FOREIGN KEY (RecipientID) REFERENCES clients(ClientID)
);




CREATE TABLE emailtemplate (
    TemplateID INT PRIMARY KEY AUTO_INCREMENT,
    Category VARCHAR(50) NOT NULL,
    Name VARCHAR(255) NOT NULL,
    Subject VARCHAR(255) NOT NULL,
    Body TEXT NOT NULL,
    Status VARCHAR(50) NOT NULL
);


CREATE TABLE sent_email_reminders (
    ReminderID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT NOT NULL,
    InvoiceID INT NOT NULL,
    ReminderSentDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID),
    FOREIGN KEY (InvoiceID) REFERENCES invoices(InvoiceID)
);



CREATE TABLE smstemplate (
    TemplateID INT AUTO_INCREMENT PRIMARY KEY,
    Category VARCHAR(255) NOT NULL,
    Name VARCHAR(255) NOT NULL,
    Body TEXT NOT NULL,
    Status VARCHAR(50) NOT NULL
);



CREATE TABLE expenses (
    ExpenseID INT PRIMARY KEY AUTO_INCREMENT,
    ExpenseDate DATE NOT NULL,
    ExpenseTypeID INT,
    ExpenseAmount DECIMAL(10, 2) NOT NULL,
    PaymentOptionID INT,
    PaymentReceiptURL VARCHAR(255), 
    Description TEXT,
    CreatedDate DATETIME,
    UpdatedDate DATETIME,
    FOREIGN KEY (ExpenseTypeID) REFERENCES expensetypes(ExpenseTypeID),
    FOREIGN KEY (PaymentOptionID) REFERENCES paymentoptions(PaymentOptionID)
);




CREATE TABLE systemlogs (
    LogID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT, 
    Timestamp DATETIME,
    EventType VARCHAR(50), 
    EventDescription TEXT,
    FOREIGN KEY (UserID) REFERENCES admins(AdminID) 
);






ALTER TABLE `clients` DROP FOREIGN KEY `clients_ibfk_1`;
ALTER TABLE `clients` ADD CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`AreaID`) REFERENCES `areas`(`AreaID`) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE `clients` DROP FOREIGN KEY `clients_ibfk_2`; 
ALTER TABLE `clients` ADD CONSTRAINT `clients_ibfk_2` FOREIGN KEY (`SubAreaID`) REFERENCES `subareas`(`SubAreaID`) ON DELETE CASCADE ON UPDATE RESTRICT;




ALTER TABLE subareas
ADD CONSTRAINT fk_SubAreas_AreaID
FOREIGN KEY (AreaID) REFERENCES areas(AreaID)
ON DELETE CASCADE;


ALTER TABLE invoices
ADD CONSTRAINT fk_Invoices_ClientID
FOREIGN KEY (ClientID) REFERENCES clients(ClientID)
ON DELETE CASCADE;

ALTER TABLE messages
ADD CONSTRAINT fk_Messages_RecipientID
FOREIGN KEY (RecipientID) REFERENCES clients(ClientID)
ON DELETE CASCADE;

ALTER TABLE payments
ADD CONSTRAINT fk_payments_ClientID
FOREIGN KEY (ClientID) REFERENCES clients(ClientID)
ON DELETE CASCADE;

ALTER TABLE advancepayments
ADD CONSTRAINT fk_advancepayments_ClientID
FOREIGN KEY (ClientID) REFERENCES clients(ClientID)
ON DELETE CASCADE;

ALTER TABLE stripepayments
ADD CONSTRAINT fk_stripepayments_ClientID
FOREIGN KEY (ClientID) REFERENCES clients(ClientID)
ON DELETE CASCADE;


ALTER TABLE invoiceproducts
ADD CONSTRAINT fk_invoiceproducts_InvoiceID
FOREIGN KEY (InvoiceID) REFERENCES invoices(InvoiceID)
ON DELETE CASCADE;




