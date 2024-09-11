#2.因修改Items而要更新的Categories
#DELIMITER //
DROP TRIGGER IF EXISTS fridge.UpdateItemsCategory;
CREATE TRIGGER UpdateItemsCategory
    BEFORE UPDATE ON Items
    FOR EACH ROW
BEGIN
    DECLARE oldCategory VARCHAR(50);
    -- 禁用外鍵檢查
    SET foreign_key_checks = 0;
    -- 獲取更新前的 Category
    SELECT Category INTO oldCategory FROM Items WHERE ItemID = NEW.ItemID;
    -- 判断是新增還是减少
    IF NEW.ItemNum != OLD.ItemNum OR NEW.Category != OLD.Category THEN
        -- 如果 ItemNum 或 Category 有改變，就進行更新
        -- 在更新 Items 表之前檢查新的 Category 是否存在於 Categories 表中，如果不存在則插入
        IF NOT EXISTS (SELECT 1 FROM Categories WHERE Category = NEW.Category) THEN
            INSERT INTO Categories (Category, CategoryNum, ItemSum)
            VALUES (NEW.Category, 0, 0);
        END IF;
        -- 先减少舊的 Category 數量
        UPDATE Categories
        SET
            CategoryNum = CategoryNum - 1,
            ItemSum = ItemSum - OLD.ItemNum
        WHERE Category = oldCategory;
        -- 判斷舊的 Category 是否還有其他的 Items，若無則刪除
        IF NOT EXISTS (SELECT 1 FROM Items WHERE Category = oldCategory AND ItemID != NEW.ItemID) THEN
            DELETE FROM Categories WHERE Category = oldCategory;
        END IF;
        -- 增加新的 Category 數量
        UPDATE Categories
        SET
            CategoryNum = CategoryNum + 1,
            ItemSum = ItemSum + NEW.ItemNum
        WHERE Category = NEW.Category;
    END IF;
    -- 啟用外鍵檢查
    SET foreign_key_checks = 1;
END;
#//

#DELIMITER ;

#3.因修改Items而要更新的Reminders
#DELIMITER //
DROP TRIGGER IF EXISTS fridge.update_reminders_expiry;
CREATE TRIGGER update_reminders_expiry
AFTER UPDATE ON Items
FOR EACH ROW
BEGIN
    UPDATE Reminders
    SET ExpiryDate = NEW.ExpiryDate,
        Description = CASE
       WHEN NEW.ExpiryDate <= CURRENT_DATE THEN '已過期'
                        WHEN NEW.ExpiryDate <= (CURRENT_DATE + INTERVAL 5 DAY) THEN '即將過期'
                        ELSE NULL
                      END
    WHERE ReminderID = NEW.ItemID;
END;
#//
#DELIMITER ;
