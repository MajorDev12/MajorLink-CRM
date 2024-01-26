



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
    PaymentDate DATETIME DEFAULT NULL,
    CreatedDate DATETIME,
    PreferedPaymentMethod INT(255) NOT NULL DEFAULT '6',
    ProfilePictureURL VARCHAR(255), 
    ActiveStatus TINYINT(1), 
    ExpireDate DATE,
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
    PhoneCode VARCHAR(100),
    LogoURL VARCHAR(255), -- Assuming a link to the logo image
    Address TEXT
    -- other company-related attributes
);


INSERT INTO companysettings (CompanyName, Motto, Email, PhoneNumber, Country, TimeZone, CurrencyName, CurrencySymbol, PhoneCode, LogoURL, Address)
VALUES (NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);




CREATE TABLE sales (
    SaleID INT PRIMARY KEY AUTO_INCREMENT,
    SaleDate DATE NOT NULL,
    AdminID INT, -- Salesperson who is an admin
    ClientID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    UnitPrice DECIMAL(10, 2) NOT NULL,
    PaymentOptionID INT,
    PaymentDate DATE,
    PaymentStatus VARCHAR(50),
    CreatedDate DATETIME,
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
    TransactionID VARCHAR(255) NOT NULL,
    PaidAmount DECIMAL(10, 2) NOT NULL,
    PaidCurrency VARCHAR(255) NULL,
    Payment_status VARCHAR(255) NOT NULL,
    ClientID INT,
    PlanID INT,
    Customer_name VARCHAR(255) NULL,
    Customer_email VARCHAR(255) NULL,
    CreatedDate DATETIME,
    UpdatedDate DATETIME,
    -- other advance payment-related attributes
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID),
    FOREIGN KEY (PlanID) REFERENCES plans(PlanID)
);




CREATE TABLE messages (
    MessageID INT PRIMARY KEY AUTO_INCREMENT,
    SenderID INT,
    RecipientID INT,
    MessageContent TEXT,
    Timestamp DATETIME,
    Status VARCHAR(50),
    -- other message-related attributes
    FOREIGN KEY (SenderID) REFERENCES clients(ClientID),
    FOREIGN KEY (RecipientID) REFERENCES clients(ClientID)
);



CREATE TABLE invoices (
    InvoiceID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT,
    InvoiceNumber VARCHAR(50),
    TotalAmount DECIMAL(10, 2) NOT NULL,
    DueDate DATE,
    Status VARCHAR(50),
    -- other invoice-related attributes
    FOREIGN KEY (ClientID) REFERENCES clients(ClientID)
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




ALTER TABLE subareas
ADD CONSTRAINT fk_SubAreas_AreaID
FOREIGN KEY (AreaID) REFERENCES areas(AreaID)
ON DELETE CASCADE;


-- Insert Admin
INSERT INTO Admins (Username, PasswordHash, FullName, Email, Status, CreatedDate)
VALUES ('admin', '12345678', 'admin', 'admin@example.com', 'Active', NOW());

-- Insert Client
INSERT INTO Clients (FirstName, LastName, PrimaryEmail, PasswordHash, CreatedDate)
VALUES ('client', 'client', 'client@example.com', '123456', NOW());
