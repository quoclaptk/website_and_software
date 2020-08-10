#!/usr/bin/env python
# -*- coding: utf-8 -*-
import time
import random
import names
from faker import Faker
from selenium import webdriver
from selenium.webdriver import ActionChains
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import Select

def random_phone_num_generator():
    first = str(random.randint(100, 999))
    second = str(random.randint(1, 888)).zfill(3)
    last = (str(random.randint(1, 9998)).zfill(4))
    while last in ['1111', '2222', '3333', '4444', '5555', '6666', '7777', '8888']:
        last = (str(random.randint(1, 9998)).zfill(4))
    return '{}{}{}'.format("098", second, last)


if __name__ == '__main__':
    count = 0
    fk = Faker()
    # 1. Optional Options (Useful for development testing)
    options = Options()
    options.add_argument("--disable-web-security")
    options.add_argument("--disable-application-cache")

    # 2. Create the Chrome Driver
    driver = webdriver.Chrome(options=options)

    # 3. Head to the JsonPlaceholder landing page
    driver.get("http://comboskii.skiijp.store/")
    #driver.get("http://myphamnhapkhau.top/sonce05/")
    #driver.save_screenshot('2.png')

    # 4. Scroll to the run-message element to bring it into view (Optional)
    actions = ActionChains(driver)
    actions.move_to_element(driver.find_element_by_css_selector("#GROUP1305"))
    actions.perform()
    time.sleep(3)
    #driver.save_screenshot('4.png')

    # 5. Interact with the site
    # driver.find_element_by_css_selector("#run-button").click() # Get the Run Button Element and Click it
    # time.sleep(1) # Small Sleep for Async request to go out and DOM be updated
    # driver.save_screenshot('5.png')

    # 6. Confirm the success message was present on the site
    # print("Congrats you've made your first call to JSONPlaceholder! 😃 🎉" == driver.find_element_by_css_selector("#run-message").text)
    # driver.save_screenshot('6.png')
    while True:
        count += 1
        print('Lượt mua hàng thứ: {0}'.format(count))
        name = driver.find_element_by_name('name')
        name.clear()
        name.send_keys(names.get_full_name())

        phone = driver.find_element_by_name('phone')
        phone.clear()
        phone.send_keys(random_phone_num_generator())
        
        Select(driver.find_element_by_name('select1')).select_by_index(random.randint(1, 4))

        address = driver.find_element_by_name('street')
        address.clear()
        address.send_keys("Chúc bạn buôn may bán đắt, gặt hái được nhiều thành công. Vui lòng liên hệ 0963836272 gặp quân để tìm được nguồn hàng giá tốt")
        
        driver.find_element_by_id('BUTTON_TEXT536').click()
        time.sleep(3)
        #driver.find_element_by_class_name('popup-close').click()
        driver.execute_script("window.history.go(-1)")
        time.sleep(2)
    # 7. Close the driver to kill the chrome instance
    #driver.close()