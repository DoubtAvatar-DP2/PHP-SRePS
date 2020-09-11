USE SREPS;

CREATE TABLE Categories (
    CategoryID INT NOT NULL AUTO_INCREMENT,
    CategoryName VARCHAR(200) NOT NULL,
    CategoryDescription VARCHAR(500),
    PRIMARY KEY(CategoryID)
);

CREATE TABLE Products (
    ProductNumber INT NOT NULL,
    ProductName	VARCHAR(200) NOT NULL,
    ProductDescription	VARCHAR(500),
    Price	DECIMAL(15,2) NOT NULL,
    CategoryID	INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(ProductNumber),
    FOREIGN KEY(CategoryID) REFERENCES Categories(CategoryID)
);

CREATE TABLE Customers (
    CustomerID INT NOT NULL AUTO_INCREMENT,
    CustomerName VARCHAR(100) NOT NULL,
    PRIMARY KEY(CustomerID)
);

CREATE TABLE SalesRecords (
    SalesRecordNumber INT NOT NULL AUTO_INCREMENT,
    SalesDate	DateTime NOT NULL,
    Comment     VARCHAR(200),
    CustomerID INT NOT NULL,
    PRIMARY KEY(SalesRecordNumber),
    FOREIGN KEY(CustomerID) REFERENCES Customers(CustomerID)
);

CREATE TABLE SaleRecordDetails (
    SalesRecordNumber INT NOT NULL AUTO_INCREMENT,
    ProductNumber INT NOT NULL,
    QuotedPrice DECIMAL(15,2) NOT NULL,
    QuantityOrdered INT NOT NULL DEFAULT 0,

    PRIMARY KEY(SalesRecordNumber, ProductNumber),
    FOREIGN KEY(SalesRecordNumber) REFERENCES SalesRecords(SalesRecordNumber),
    FOREIGN KEY(ProductNumber) REFERENCES Products(ProductNumber)
);


USE SREPS;

INSERT INTO Categories (CategoryID, CategoryName, CategoryDescription)
VALUES
(1, 'Vitamins', 'With over 1,000 vitamins & supplements online and a huge range of brands to choose from, PHP is the best supplement store online for all your health needs'),
(2, 'Fragrances','Buy Perfume & Cologne at PHP and enjoy huge discounts across the entire range.'),
(3, 'Beauty', 'Buy Beauty products at PHP and enjoy huge discounts across the entire range.'),
(4, 'Weight loss', 'Buy Weight loss products at PHP and enjoy huge discounts across the entire range.'),
(5, 'Oral Hygiene and Dental Care', 'Buy Oral Hygiene and Dental Care products at PHP and enjoy huge discounts across the entire range.'),
(6, 'Baby Care','Buy Baby Care products at PHP and enjoy huge discounts across the entire range.'),
(7, 'Sexual Health', 'Buy Sexual Health products at PHP and enjoy huge discounts across the entire range.'),
(8, 'Medicine', 'Buy Medicine products at PHP and enjoy huge discounts across the entire range.');

INSERT INTO Products (ProductNumber, ProductName, ProductDescription, Price, CategoryID) 
VALUES
(
    2655921, 
    "Nicorette Quit Smoking QuickMist Mouth Spray Freshmint Triple 150 Sprays (13.2mL x 3)", 
    "Nicorette QuickMist Nicotine Mouth Spray Freshmint for fast nicotine craving relief. Nicotine is dispersed in a fine mist and is absorbed fast into the body. Starts to relieve cravings from 30 seconds*",
    52.99,
    8
),

(
    2609997, 
    'Voltaren Emulgel Muscle and Back Pain Relief 180g (Exclusive Size)', 
    'Voltaren Emulgel is for the temporary relief of local pain and inflammation.', 
    27.99, 
    8
),

(
    2609910,
    'Telfast 180mg 70 Tablets - Hayfever allergy relief',
    'Fast, non drowsy relief of the symptoms of hayfever allergies and itchy skin rash or hives.',
    29.99,
    8
),

(
    2489407,
    'Panamax 500mg 100 Tablets',
    "For the temporary relief of pain and discomfort in arthritis, headache, muscular and neuralgic conditions. Reduces fever.",
0.69,
8
),

(
    2654442,
    "Bio Island Milk Calcium Kids 90 Capsules",
    "Combines calcium sourced from cows' milk and vitamin D3, which help support healthy bones and teeth.",
    19.99,
    1
),

(
    2580506,
    "Blackmores Pregnancy and Breastfeeding Gold 180 Capsules",
    "Formulated with 20 important nutrients to support a healthy pregnancy including Folic acid, Iron, DHA, and Vitamin D.",
    31.99,
    1
),

(
    2636743,
    "Ostelin Vitamin D3 1000IU - Vitamin D - 300 Capsules Exclusive Size",
    "Ostelin Vitamin D3 1000IU helps keep muscles and bones strong.",
    24.99,
    1
),

(
    2516140,
    "Cenovis Sugarless C 500mg - Chewable Vitamin C - 300 Tablets Value Pack",
    "Cenovis® Sugarless C 500mg is a Vitamin C formula with orange flavour in a chewable tablet. As Vitamin C is not stored in the body, regular intake is desirable.",
    12.49,
    1
),

(
    2676393,
    "Protein World Vegan Slender Blend Chocolate 800g",
    "High in protein, packing a huge 26g with less than 0.5g of sugar and fat per serving. This is the perfect, natural shake",
    23.99,
    4  
),

(
    2670552,
    'Optifast VLCD Shake Chocolate 18 x 53g',
    "OPTIFAST VLCD Chocolate Shakes (Pack of 18) are part of a nutritionally complete, very low calorie diet program",
    59.99,
    4
),

(
    2680852,
    "Optifast VLCD Shake Assorted Pack 10 x 53g New Flavours",
    "The OPTIFAST VLCD Assorted Shakes (Pack of 10) is a great way to try the variety of flavours in the OPTIFAST VLCD Shakes range.",
    48.49,
    4
),

(
    2679510,
    "Naturopathica Fatblaster Clinical 60 Capsules",
    "",
    34.99,
    4
),

(
    2681803,
    "Listerine Total Care Mouthwash 1 Litre + 500ml Value Pack",
    "LISTERINE® is especially effective when used morning and evening after brushing your teeth.",
    11.99,
    5
),

(
    2501583,
    "Oral B Essential Floss 2x50m",
    "Oral-B Essential Dental Floss removes plaque-bacteria in interdental space and at the gum line, helping your gums and your teeth stay healthy.",
    5.99,
    5
),

(
    2673563,
    "Colgate Peppa Pig Kids Toothpaste Sparkling Mint Gel 2-5 years 80g",
    "Colgate Peppa Pig Sparkling Mint Gel is an effective low fluoride, sugar free toothpaste that has been specially designed for children aged 2-5 years.",
    1.99,
    5
),

(
    2626655,
    "Colgate Toothbrush 360 Degree Medium Twin Pack",
    "Colgate 360° Compact Toothbrush medium with a cheek and tongue cleaner reduces 151% more bacteria for a whole mouth clean and improved gum health",
    4.99,
    5
),

(
    2672893,
    "Aveeno Baby Daily Moisture Fragrance Free Lotion 532mL",
    "Aveeno Baby Daily Lotion is fragrance free with no added parabens and phthalates. It moisturises & protects delicate skin for 24 hours. This hypoallergenic and fragrance free moisturiser is even gentle enough for newborns.",
    12.59,
    6
),

(
    2663205,
    "NAN Supreme Formula 3 800g",
    "NAN SUPREME 3 Toddler Milk Drink is a premium, nutritional milk supplement designed for toddlers from 1 year of age when energy and nutrient intakes may not be adequate. ",
    21.49,
    6
),

(
    2670499,
    "Bubs Goat Toddler Formula 800g",
    "Bubs Australian Goat Milk Stage 3 is exclusively formulated for tiny Australian tummies aged 12 to 36 months.",
    37.49,
    6   
),

(
    2678514,
    "Curash Babycare Simply Water Wipes 6 x 80",
    "Curash™ Babycare Simply Water baby wipes contain 99% water and have been specially formulated for the most sensitive skin.",
    19.99,
    6
),

(
    2669234,
    "LifeStyles Silky Smooth Lubricant 200g",
    "LifeStyles Silky Smooth Lubricant is water-based that formul,ated for comfort during intercourse.",
    9.99,
    7
),

(
    2677738,
    "Four Seasons Massage Oil 150ml",
    "Four Seasons Massage Oil is a unique blend of non-greasy natural oils lightly scented with Lavender and Ylang Ylang.",
    6.99,
    7
),

(
    2686921,
    "LifeStyles Condoms Ultra Thin 20 Pack",
    "LifeStyles Ultra Thin Condom is an easy-fit with a reservoir end, lubricated, non-spermicidal, smooth surface, and natural in colour.",
    9.99,
    7
),

(
    2684082,
    "CeraVe Hydrating Cleanser 473ml",
    "A moisturising cleansing lotion for normal to dry skin with 3 essential ceramides to help strengthen the skin's natural barrier.",
    18.99,
    3
),

(
    2662823,
    "Aveeno Active Naturals Daily Moisturising Fragrance Free Body Lotion 1 Litre",
    "Aveeno Active Naturals Daily Moisturising Body Lotion relieves and protects dry sensitive skin, moisturises for 24 hours and is fragrance free.",
    15.39,
    3
),

(
    2673775,
    "Olay Regenerist Advanced Anti-Ageing Micro-Sculpting Face Cream 50g",
    "Olay Regenerist Micro-Sculpting Face Cream Moisturiser firms, plumps & reduces wrinkles. Firmer, plumper skin in 28 days!",
    28.99,
    3
),

(
    2497435,
    "Lolita Lempicka Eau De Parfum 100ml",
    "Lolita Lempicka Perfume by Lolita Lempicka, Launched by the design house of lolita lempicka in 1997, lolita lempicka is classified as a refreshing, oriental, woody fragrance.",
    49.99,
    3
);


INSERT INTO Customers(CustomerID, CustomerName)
VALUES 
(1, "Anonymous");

INSERT INTO Customers(CustomerID, CustomerName)
VALUES
(2, "Alex"),
(3, "Bob"),
(4, "Terry"),
(5, "Yvonne"),
(6, "Luccie"),
(7, "Zarchary"),
(10, "Sean");

INSERT INTO SalesRecords (SalesRecordNumber, SalesDate, CustomerID)
VALUES
(1000, "2020-09-11", 1),
(1001, "2020-09-10", 1),
(1002, "2020-09-09", 2),
(1003, "2020-09-09", 3),
(1004, "2020-09-08", 10),
(1005, "2020-09-07", 7),
(1006, "2020-09-05", 5);

INSERT INTO SaleRecordDetails(SalesRecordNumber, ProductNumber, QuotedPrice, QuantityOrdered)
VALUES
(1000, 2655921, 52.99, 1),
(1000, 2489407, 0.69, 5),
(1001, 2497435, 49.99, 2),
(1001, 2673775, 28.99, 1),
(1002, 2677738, 6.99, 5),
(1002, 2670499, 37.49, 1),
(1002, 2626655, 4.99, 10),
(1002, 2654442, 19.99, 8),
(1002, 2609910, 29.99, 1),
(1002, 2516140, 12.49, 10),
(1003, 2654442, 19.99, 4),
(1003, 2626655, 4.99, 1),
(1004, 2655921, 52.99, 1),
(1005, 2609997, 27.69, 1),
(1006, 2670499, 37.49, 2),
(1006, 2663205, 21.49, 1);

