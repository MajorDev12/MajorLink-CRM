



CREATE TABLE admins (
    AdminID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(50) NOT NULL,
    PasswordHash VARCHAR(255) NOT NULL, -- Store hashed passwords securely
    FullName VARCHAR(255) NOT NULL,
    Email VARCHAR(255),
    Phone VARCHAR(20),
    Status VARCHAR(50), -- Admin account status (e.g., Active, Inactive)
    LastLogin DATETIME,
    CreatedDate DATETIME,
    UpdatedDate DATETIME,
    ProfilePictureURL VARCHAR(255) -- Assuming a link to the profile picture
    -- other admin-related attributes
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
    -- other plan-related attributes
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
    PasswordHash VARCHAR(255), -- Store hashed passwords securely
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
    -- other client-related attributes
    FOREIGN KEY (AreaID) REFERENCES areas(AreaID),
    FOREIGN KEY (SubAreaID) REFERENCES subareas(SubAreaID),
    FOREIGN KEY (PlanID) REFERENCES plans(PlanID)
);


CREATE TABLE clientaccounts (
    AccountID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT,
    Balance DECIMAL(10, 2) DEFAULT 0.00,
    TotalDeposits DECIMAL(10, 2) DEFAULT 0.00,
    TotalWithdrawals DECIMAL(10, 2) DEFAULT 0.00,
    LastTransactionDate DATE,
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE paymentoptions (
    PaymentOptionID INT PRIMARY KEY AUTO_INCREMENT,
    PaymentOptionImg VARCHAR(50) NOT NULL,
    PaymentOptionName VARCHAR(50) NOT NULL,
    PaymentOptionQuote VARCHAR(50) NOT NULL
    -- other payment option-related attributes
);



CREATE TABLE products (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    ProductName VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    Description TEXT
    -- other product-related attributes
);




CREATE TABLE expensetypes (
    ExpenseTypeID INT PRIMARY KEY AUTO_INCREMENT,
    ExpenseTypeName VARCHAR(50) NOT NULL,
    Description TEXT
    -- other expense type-related attributes
);



CREATE TABLE companysettings (
    SettingID INT PRIMARY KEY AUTO_INCREMENT,
    CompanyName VARCHAR(255),
    Motto TEXT,
    Email VARCHAR(255),
    PhoneNumber VARCHAR(20),
    Country VARCHAR(100),
    TimeZone VARCHAR(50),
    CurrencyName VARCHAR(100),
    CurrencySymbol VARCHAR(100),
    CurrencyCode VARCHAR(100),
    PhoneCode VARCHAR(100),
    LogoURL VARCHAR(255),
    Address TEXT
    -- other company-related attributes
);


INSERT INTO companysettings (CompanyName, Motto, Email, PhoneNumber, Country, TimeZone, CurrencyName, CurrencySymbol, PhoneCode, LogoURL, Address)
VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);




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
    -- other sale-related attributes
    FOREIGN KEY (AdminID) REFERENCES admins(AdminID),
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID),
    FOREIGN KEY (ProductID) REFERENCES products(ProductID),
    FOREIGN KEY (PaymentOptionID) REFERENCES paymentoptions(PaymentOptionID)
);






CREATE TABLE payments (
    PaymentID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT,
    PlanID INT,
    PaymentAmount DECIMAL(10, 2) NOT NULL,
    PaymentStatus VARCHAR(50),
    PaymentDate DATE,
    PaymentOptionID INT,
    InstallationFees INT,
    -- other payment-related attributes
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
    -- other advance payment-related attributes
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
    -- other advance payment-related attributes
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
    -- other message-related attributes
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
    -- other invoice-related attributes
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID)
);



CREATE TABLE invoiceProducts (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    InvoiceID INT,
    Name VARCHAR(255),
    Volume VARCHAR(50),
    Qty INT,
    Price DECIMAL(10, 2) NULL,
    Amount DECIMAL(10, 2) NULL,
    subTotal DECIMAL(10, 2) NULL,
    -- other product-related attributes
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
    -- other email-related attributes
    FOREIGN KEY (SenderID) REFERENCES clients(ClientID),
    FOREIGN KEY (RecipientID) REFERENCES clients(ClientID)
);




CREATE TABLE emailTemplate (
    TemplateID INT PRIMARY KEY AUTO_INCREMENT,
    Category VARCHAR(50) NOT NULL,
    Name VARCHAR(255) NOT NULL,
    Subject VARCHAR(255) NOT NULL,
    Body TEXT NOT NULL,
    Status VARCHAR(50) NOT NULL
);


CREATE TABLE smsTemplate (
    TemplateID INT AUTO_INCREMENT PRIMARY KEY,
    Category VARCHAR(255) NOT NULL,
    Name VARCHAR(255) NOT NULL,
    Body TEXT NOT NULL,
    Status VARCHAR(50) NOT NULL
    -- Status ENUM('Active', 'Inactive') NOT NULL
);



CREATE TABLE expenses (
    ExpenseID INT PRIMARY KEY AUTO_INCREMENT,
    ExpenseDate DATE NOT NULL,
    ExpenseTypeID INT,
    ExpenseAmount DECIMAL(10, 2) NOT NULL,
    PaymentOptionID INT,
    PaymentReceiptURL VARCHAR(255), -- Assuming a link to the payment receipt
    Description TEXT,
    CreatedDate DATETIME,
    UpdatedDate DATETIME,
    -- other expense-related attributes
    FOREIGN KEY (ExpenseTypeID) REFERENCES expensetypes(ExpenseTypeID),
    FOREIGN KEY (PaymentOptionID) REFERENCES paymentoptions(PaymentOptionID)
);




CREATE TABLE systemlogs (
    LogID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT, -- Reference to the user responsible for the action (can be an admin or client)
    Timestamp DATETIME,
    EventType VARCHAR(50), -- Describes the type of event or action
    EventDescription TEXT,
    -- other log-related attributes
    FOREIGN KEY (UserID) REFERENCES admins(AdminID) -- Reference to the Admins table
);



DELIMITER //

CREATE TRIGGER before_delete_area
BEFORE DELETE ON areas
FOR EACH ROW
BEGIN
    UPDATE clients SET AreaID = NULL WHERE AreaID = OLD.AreaID;
    UPDATE clients SET SubAreaID = NULL WHERE SubAreaID = OLD.SubAreaID;
    -- Add more update statements for other related tables if needed
END;

//

DELIMITER ;



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

ALTER TABLE stripepayments
ADD CONSTRAINT fk_stripepayments_ClientID
FOREIGN KEY (ClientID) REFERENCES clients(ClientID)
ON DELETE CASCADE;


ALTER TABLE invoiceproducts
ADD CONSTRAINT fk_invoiceproducts_InvoiceID
FOREIGN KEY (InvoiceID) REFERENCES invoices(InvoiceID)
ON DELETE CASCADE;


-- Insert Admin
INSERT INTO Admins (Username, PasswordHash, FullName, Email, Status, CreatedDate)
VALUES ('admin', '12345678', 'admin', 'admin@example.com', 'Active', NOW());

-- Insert Client
INSERT INTO Clients (FirstName, LastName, PrimaryEmail, PasswordHash, CreatedDate)
VALUES ('client', 'client', 'client@example.com', '123456', NOW());



INSERT INTO emailTemplate (Category, Name, Subject, Body, Status) 
VALUES 
    ('Promotion', 'Promotional Email', 'Special Offer Inside!', 'Dear Customer, Check out our latest offers. Don\'t miss out!', 'Active'),
    ('Reminder', 'Payment Reminder', 'Reminder: Payment Due', 'Dear Customer, This is a friendly reminder that your payment is due soon. Please ensure timely payment. Thank you.', 'Active'),
    ('Welcome', 'Welcome Email', 'Welcome to Our Platform', 'Dear New User, Welcome to our platform! We are thrilled to have you with us. If you have any questions, feel free to reach out.', 'Active');



INSERT INTO smsTemplate (Category, Name, Body, Status) 
VALUES 
    ('Promotion', 'Holiday Sale', 'Get 20% off on all items this weekend! Limited time offer.', 'Active'),
    ('Reminder', 'Payment Due', 'Friendly reminder: Your payment is due tomorrow. Please make sure to submit it on time.', 'Active'),
    ('Welcome', 'New User Greeting', 'Welcome to our platform! Get started with our amazing features today.', 'Active');
