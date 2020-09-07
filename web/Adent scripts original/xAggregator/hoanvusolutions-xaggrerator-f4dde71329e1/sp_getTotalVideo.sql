DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getVideosTotal` (`sourceSiteId` INT(11), `categoryId` INT(11), `tagId` INT(11)) 

BEGIN
  CREATE TEMPORARY TABLE IF NOT EXISTS tVideoTags(id INTEGER NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), INDEX(id));
  IF tagId <> 0 THEN
    INSERT INTO tVideoTags SELECT vt.videoId AS Id FROM videos_tags vt WHERE vt.tagId = tagId;
  END IF;
  
  CREATE TEMPORARY TABLE IF NOT EXISTS tVideoSearch(total INT(11));
  
  CASE
    WHEN sourceSiteId <> 0 AND categoryId <> 0 THEN 
      INSERT INTO tVideoSearch
      SELECT COUNT(v.Id) AS total
      FROM videos AS v 
          JOIN videos_categories AS vc ON v.Id = vc.videoId 
      WHERE vc.catId = categoryId AND v.sourceSiteId = sourceSiteId AND v.isActive = 1;
    WHEN sourceSiteId <> 0 AND tagId <> 0 THEN
      INSERT INTO tVideoSearch
      SELECT COUNT(v.Id) AS total
      FROM videos AS v 
      WHERE v.sourceSiteId = sourceSiteId AND v.isActive = 1 AND v.Id IN (SELECT Id FROM tVideoTags);
    WHEN sourceSiteId <> 0 AND categoryId = 0 AND tagId = 0 THEN
      INSERT INTO tVideoSearch
      SELECT COUNT(v.Id) AS total
      FROM videos AS v 
      WHERE v.sourceSiteId = sourceSiteId AND v.isActive = 1;
  WHEN sourceSiteId = 0 AND categoryId <> 0 THEN 
      INSERT INTO tVideoSearch
      SELECT COUNT(v.Id) AS total
      FROM videos AS v 
          JOIN videos_categories AS vc ON v.Id = vc.videoId
      WHERE vc.catId = categoryId AND v.isActive = 1;
    WHEN sourceSiteId = 0 AND tagId <> 0 THEN
      INSERT INTO tVideoSearch
      SELECT COUNT(v.Id) AS total
      FROM videos AS v 
      WHERE v.isActive = 1 AND v.Id IN (SELECT Id FROM tVideoTags);
    WHEN sourceSiteId = 0 AND categoryId = 0 AND tagId = 0 THEN
      INSERT INTO tVideoSearch
      SELECT COUNT(v.Id) AS total
      FROM videos AS v 
      WHERE v.isActive = 1;
  END CASE;
  
  SELECT total FROM tVideoSearch;
  
  DROP TEMPORARY TABLE tVideoTags;
  DROP TEMPORARY TABLE tVideoSearch;
END$$

DELIMITER ;