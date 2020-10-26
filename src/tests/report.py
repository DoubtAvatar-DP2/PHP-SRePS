import unittest
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
import datetime

def check_valid_date_format(datestr):
    year, month, day= datestr.split('-')
    isValidDate = True
    try :
        datetime.datetime(int(year),int(month),int(day))
    except ValueError :
        isValidDate = False

    if(isValidDate) :
        print ("date is valid format(YYYY-MM-DD) : " + datestr)
    else :
        print ("date is not valid format(YYYY-MM-DD) : " +datestr)
    
    return isValidDate

def check_decimal(string):
    try:
        a = float(string)
        return True
    except Exception:
        return False

class TestReportFrontEnd(unittest.TestCase):

    def setUp(self):
        self.driver = webdriver.Chrome(r"chromedriver.exe")

    def test_title(self):
        driver = self.driver
        driver.get("http://localhost:8080/report.php")
        self.assertIn("PHP-SRePS", driver.title, "wrong title")
        header = driver.find_element_by_class_name("h2")
        self.assertIn("Report", header.get_attribute('innerHTML'), "wrong header")
    
    def test_generate_table(self):
        driver = self.driver
        driver.get("http://localhost:8080/report.php")
        driver.find_element_by_id("item").click()
        
        product_textbox = driver.find_element_by_id("ITEMID")
        category_textbox = driver.find_element_by_id("CATEGORYID")
        
        self.assertEqual('true', category_textbox.get_attribute("disabled"))
        self.assertEqual("", product_textbox.get_attribute("value"))

        product_textbox.send_keys("2489407")
        
        # click to generate report
        driver.find_element_by_id("report").click()

        report_title = driver.find_element_by_id("reportTitle")
        self.assertEqual("Panamax 500mg 100 Tablets Sales Report", report_title.get_attribute("innerHTML"))

        self.assertGreaterEqual(len(driver.find_element_by_id("reportTable").find_elements_by_tag_name("tr")), 2, "There must be at least one record row")

        # traverse through table and check
        for i, row in enumerate(driver.find_element_by_id("reportTable").find_elements_by_tag_name("tr")):
            if i == 0: continue
            cells = row.find_elements_by_tag_name("td")
            
            # date column
            self.assertEqual(check_valid_date_format(cells[0].get_attribute("innerHTML")), True)
            
            # number of item column
            self.assertEqual(cells[1].get_attribute("innerHTML").isnumeric(), True)
            
            # total sales column
            self.assertEqual(check_decimal(cells[2].get_attribute("innerHTML")), True)

    def test_generate_chart(self):
        driver = self.driver
        driver.get("http://localhost:8080/report.php")
        driver.find_element_by_id("item").click()
        
        product_textbox = driver.find_element_by_id("ITEMID")
        product_textbox.send_keys("2489407")
        
        # click to generate report
        driver.find_element_by_id("report").click()

        # chart has not been blank
        self.assertNotEqual(driver.find_element_by_id("chart").get_attribute("innerHTML"), "")
    
    def test_not_found_record(self):
        driver = self.driver
        driver.get("http://localhost:8080/report.php")
        driver.find_element_by_id("item").click()
        

        product_textbox = driver.find_element_by_id("ITEMID")
        # item id not found
        product_textbox.send_keys("0000000")
        
        # click to generate report
        driver.find_element_by_id("report").click()

        self.assertIn("No record found", driver.find_element_by_id("error").find_element_by_tag_name("div").get_attribute("innerHTML"))

    
    def test_valid_date_range(self):
        driver = self.driver
        driver.get("http://localhost:8080/report.php")
        
        start_date = driver.find_element_by_id("recorddatestart").get_attribute("value")
        end_date = driver.find_element_by_id("recorddateend").get_attribute("value")

        driver.find_element_by_id("ITEMID").send_keys("2489407")
        
        # click to generate report
        driver.find_element_by_id("report").click()

        start_year, start_month, start_day = start_date.split('-')
        start_date = datetime.datetime(int(start_year),int(start_month),int(start_day))

        end_year, end_month, end_day = end_date.split('-')
        end_date = datetime.datetime(int(end_year),int(end_month),int(end_day))

        print(start_date)
        print(end_date)
        # traverse through table and check
        for i, row in enumerate(driver.find_element_by_id("reportTable").find_elements_by_tag_name("tr")):
            if i == 0: continue
            cells = row.find_elements_by_tag_name("td")

            # date column
            self.assertEqual(check_valid_date_format(cells[0].get_attribute("innerHTML")), True)
            
            year, month, day = cells[0].get_attribute("innerHTML").split('-')
            
            record_date = datetime.datetime(int(start_year), int(start_month), int(start_day))

            self.assertGreaterEqual(record_date, start_date)
            self.assertLessEqual(record_date, end_date)

    def tearDown(self):
        self.driver.close()

if __name__ == "__main__":
    unittest.main()