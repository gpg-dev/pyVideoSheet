DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getVideosPagination` (`sourceSiteId` INT(11), `categoryId` INT(11), `tagId` INT(11), `orderby` VARCHAR(255), `skip` INT(5), `take` INT(5))

BEGIN
  SET @orderColumn = orderby;

  CREATE TEMPORARY TABLE IF NOT EXISTS tVideoTags(id INTEGER NOT NULL AUTO_INCREMENT, PRIMARY KEY(id), INDEX(id));
  IF tagid <> 0 THEN
    INSERT INTO tVideoTags SELECT vt.videoid AS id FROM videos_tags vt WHERE vt.tagid = tagid;
  END IF;

  CREATE TEMPORARY TABLE IF NOT EXISTS tVideoSearch(id INTEGER NOT NULL AUTO_INCREMENT, title VARCHAR(255), url VARCHAR(2000),
          image VARCHAR(255), duration INT(8), numOfClicks INT(8), PRIMARY KEY(id), INDEX(id));

  CASE
    WHEN sourceSiteid <> 0 AND categoryid <> 0 THEN
      INSERT INTO tVideoSearch
      SELECT v.id, v.title, v.url, v.image, v.duration, v.numOfClicks
       FROM videos AS v
          JOIN videos_categories AS vc ON v.Id = vc.videoId
      WHERE vc.catId = categoryId AND v.sourceSiteId = sourceSiteId AND v.isActive = 1
      GROUP BY v.id
      ORDER BY @orderColumn DESC
      LIMIT skip, take;
    WHEN sourceSiteid <> 0 AND tagid <> 0 THEN
      INSERT INTO tVideoSearch
      SELECT v.id, v.title, v.url, v.image, v.duration, v.numOfClicks
      FROM videos AS v
      WHERE v.sourceSiteId = sourceSiteId AND v.isActive = 1 AND v.Id IN (SELECT Id FROM tVideoTags)
      GROUP BY v.id
      ORDER BY @orderColumn DESC
      LIMIT skip, take;
    WHEN sourceSiteid <> 0 AND categoryid = 0 AND tagid = 0 THEN
      INSERT INTO tVideoSearch
      SELECT v.id, v.title, v.url, v.image, v.duration, v.numOfClicks
      FROM videos AS v
      WHERE v.sourceSiteId = sourceSiteId AND v.isActive = 1
      GROUP BY v.id
      ORDER BY @orderColumn DESC
      LIMIT skip, take;
  WHEN sourceSiteid = 0 AND categoryid <> 0 THEN
      INSERT INTO tVideoSearch
      SELECT v.id, v.title, v.url, v.image, v.duration, v.numOfClicks
      FROM videos AS v
          JOIN videos_categories AS vc ON v.Id = vc.videoId
      WHERE vc.catId = categoryId AND v.isActive = 1
      GROUP BY v.id
      ORDER BY @orderColumn DESC
      LIMIT skip, take;
    WHEN sourceSiteid = 0 AND tagid <> 0 THEN
      INSERT INTO tVideoSearch
      SELECT v.id, v.title, v.url, v.image, v.duration, v.numOfClicks
      FROM videos AS v
      WHERE v.isActive = 1 AND v.Id IN (SELECT Id FROM tVideoTags)
      GROUP BY v.id
      ORDER BY @orderColumn DESC
      LIMIT skip, take;
    WHEN sourceSiteid = 0 AND categoryid = 0 AND tagid = 0 THEN
      INSERT INTO tVideoSearch
      SELECT v.id, v.title, v.url, v.image, v.duration, v.numOfClicks
      FROM videos AS v
      WHERE v.isActive = 1
      GROUP BY v.id
      ORDER BY @orderColumn DESC
      LIMIT skip, take;
  END CASE;

  SELECT * FROM tVideoSearch;

  DROP TEMPORARY TABLE tVideoTags;
  DROP TEMPORARY TABLE tVideoSearch;
END$$

DELIMITER ;