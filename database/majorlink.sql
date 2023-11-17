



CREATE TABLE Admins (
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




CREATE TABLE Areas (
    AreaID INT PRIMARY KEY AUTO_INCREMENT,
    AreaName VARCHAR(255) NOT NULL,
    Latitude DECIMAL(10, 6),
    Longitude DECIMAL(10, 6),
    Description TEXT
);




CREATE TABLE SubAreas (
    SubAreaID INT PRIMARY KEY AUTO_INCREMENT,
    SubAreaName VARCHAR(255) NOT NULL,
    AreaID INT,
    Latitude DECIMAL(10, 6),
    Longitude DECIMAL(10, 6),
    Description TEXT,
    FOREIGN KEY (AreaID) REFERENCES Areas(AreaID)
);




CREATE TABLE Clients (
    ClientID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(255) NOT NULL,
    LastName VARCHAR(255) NOT NULL,
    Email VARCHAR(255),
    Phone VARCHAR(20),
    SecondaryPhone VARCHAR(20),
    PasswordHash VARCHAR(255) NOT NULL, -- Store hashed passwords securely
    AreaID INT,
    SubAreaID INT,
    Latitude DECIMAL(10, 6),
    Longitude DECIMAL(10, 6),
    LastLogin DATETIME,
    CreatedDate DATETIME,
    ProfilePictureURL VARCHAR(255), -- Assuming a link to the profile picture
    -- other client-related attributes
    FOREIGN KEY (AreaID) REFERENCES Areas(AreaID),
    FOREIGN KEY (SubAreaID) REFERENCES SubAreas(SubAreaID)
);


CREATE TABLE PaymentOptions (
    PaymentOptionID INT PRIMARY KEY AUTO_INCREMENT,
    PaymentOptionName VARCHAR(50) NOT NULL
    -- other payment option-related attributes
);



CREATE TABLE Products (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    ProductName VARCHAR(255) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2) NOT NULL
    -- other product-related attributes
);



CREATE TABLE Plans (
    PlanID INT PRIMARY KEY AUTO_INCREMENT,
    PlanName VARCHAR(255) NOT NULL,
    planVolume VARCHAR(255) NOT NULL,
    Price INT(255) NOT NULL
    -- other plan-related attributes
);




CREATE TABLE ExpenseTypes (
    ExpenseTypeID INT PRIMARY KEY AUTO_INCREMENT,
    ExpenseTypeName VARCHAR(50) NOT NULL,
    Description TEXT
    -- other expense type-related attributes
);


CREATE TABLE CompanySettings (
    SettingID INT PRIMARY KEY AUTO_INCREMENT,
    CompanyName VARCHAR(255) NOT NULL,
    Motto TEXT,
    Email VARCHAR(255),
    PhoneNumber VARCHAR(20),
    Country VARCHAR(100),
    TimeZone VARCHAR(50),
    LogoURL VARCHAR(255), -- Assuming a link to the logo image
    Address TEXT
    -- other company-related attributes
);



CREATE TABLE Sales (
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
    FOREIGN KEY (AdminID) REFERENCES Admins(AdminID),
    FOREIGN KEY (ClientID) REFERENCES Clients(ClientID),
    FOREIGN KEY (ProductID) REFERENCES Products(ProductID),
    FOREIGN KEY (PaymentOptionID) REFERENCES PaymentOptions(PaymentOptionID)
);






CREATE TABLE Payments (
    PaymentID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT,
    PaymentAmount DECIMAL(10, 2) NOT NULL,
    PaymentStatus VARCHAR(50),
    PaymentDate DATE,
    PaymentOptionID INT,
    -- other payment-related attributes
    FOREIGN KEY (ClientID) REFERENCES Clients(ClientID),
    FOREIGN KEY (PaymentOptionID) REFERENCES PaymentOptions(PaymentOptionID)
);



CREATE TABLE AdvancePayments (
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
    FOREIGN KEY (PaymentOptionID) REFERENCES PaymentOptions(PaymentOptionID),
    FOREIGN KEY (PlanID) REFERENCES Plans(PlanID)
);




CREATE TABLE Messages (
    MessageID INT PRIMARY KEY AUTO_INCREMENT,
    SenderID INT,
    RecipientID INT,
    MessageContent TEXT,
    Timestamp DATETIME,
    Status VARCHAR(50),
    -- other message-related attributes
    FOREIGN KEY (SenderID) REFERENCES Clients(ClientID),
    FOREIGN KEY (RecipientID) REFERENCES Clients(ClientID)
);



CREATE TABLE Invoices (
    InvoiceID INT PRIMARY KEY AUTO_INCREMENT,
    ClientID INT,
    InvoiceNumber VARCHAR(50),
    TotalAmount DECIMAL(10, 2) NOT NULL,
    DueDate DATE,
    Status VARCHAR(50),
    -- other invoice-related attributes
    FOREIGN KEY (ClientID) REFERENCES Clients(ClientID)
);






CREATE TABLE Emails (
    EmailID INT PRIMARY KEY AUTO_INCREMENT,
    SenderID INT,
    RecipientID INT,
    Subject VARCHAR(255),
    EmailContent TEXT,
    Timestamp DATETIME,
    -- other email-related attributes
    FOREIGN KEY (SenderID) REFERENCES Clients(ClientID),
    FOREIGN KEY (RecipientID) REFERENCES Clients(ClientID)
);






CREATE TABLE Expenses (
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
    FOREIGN KEY (ExpenseTypeID) REFERENCES ExpenseTypes(ExpenseTypeID),
    FOREIGN KEY (PaymentOptionID) REFERENCES PaymentOptions(PaymentOptionID)
);




CREATE TABLE SystemLogs (
    LogID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT, -- Reference to the user responsible for the action (can be an admin or client)
    Timestamp DATETIME,
    EventType VARCHAR(50), -- Describes the type of event or action
    EventDescription TEXT,
    -- other log-related attributes
    FOREIGN KEY (UserID) REFERENCES Admins(AdminID) -- Reference to the Admins table
);




ALTER TABLE SubAreas
ADD CONSTRAINT fk_SubAreas_AreaID
FOREIGN KEY (AreaID) REFERENCES Areas(AreaID)
ON DELETE CASCADE;


