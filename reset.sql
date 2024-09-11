#讓Categories更新資料
#1.讓Categories在Items插入資料時要更新
#DELIMITER //

-- 建立 AFTER INSERT 的觸發器
DROP TRIGGER IF EXISTS fridge.UpdateCategoriesInsert;
CREATE TRIGGER UpdateCategoriesInsert
AFTER INSERT
ON Items
FOR EACH ROW
BEGIN
    -- 更新插入的 CategorySum
    UPDATE Categories
    SET ItemSum = COALESCE(ItemSum, 0) + NEW.ItemNum
    WHERE Category = NEW.Category;

    -- 檢查 Category 是否已存在於 Categories 表中
    IF (SELECT COUNT(*) FROM Categories WHERE Category = NEW.Category) = 0 THEN
        -- 如果 Category 不存在，插入一條新記錄，並將 ItemNum 賦值給 ItemSum
        INSERT INTO Categories (Category, CategoryNum, ItemSum)
        VALUES (NEW.Category, 1, NEW.ItemNum);
    ELSE
        -- 如果 Category 已經存在，更新 CategoryNum
        UPDATE Categories
        SET CategoryNum = (SELECT COUNT(ItemID) FROM Items WHERE Category = NEW.Category)
        WHERE Category = NEW.Category;
    END IF;
END ;
#//
#DELIMITER ;


#2.因新增Items而新增Reminders的trigger
#DELIMITER //
DROP TRIGGER IF EXISTS fridge.update_reminders_on_insert;
CREATE TRIGGER update_reminders_on_insert
AFTER INSERT ON Items
FOR EACH ROW
BEGIN
    DECLARE today DATE;
    SET today = CURDATE();

    -- 更新已過期的資料
    UPDATE Reminders
    SET Description = '已過期'
    WHERE ReminderID = NEW.ItemID AND NEW.ExpiryDate <= today;

    -- 插入快過期的資料
    INSERT INTO Reminders (ReminderID, ExpiryDate, Description)
    SELECT 
        NEW.ItemID, 
        NEW.ExpiryDate, 
        CASE
            WHEN NEW.ExpiryDate <= today THEN '已過期'
            WHEN NEW.ExpiryDate <= DATE_ADD(today, INTERVAL 5 DAY) THEN '即將過期'
            ELSE NULL
        END;

END;
#//

#DELIMITER ;