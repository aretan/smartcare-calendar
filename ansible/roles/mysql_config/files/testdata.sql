START TRANSACTION;

INSERT INTO `shoken` VALUES ('825-678901', 'てすと１', '2019-01-06', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `shoken` VALUES ('825-678902', 'てすと２', '2018-04-16', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `shoken` VALUES ('825-678903', '空の人', '2018-05-01', '', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

INSERT INTO `ukeban` VALUES ('E01-1908-8178901', '825-678901', '2019-09-01', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `ukeban` VALUES ('E01-1908-8178902', '825-678901', '2019-02-01', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `ukeban` VALUES ('E01-1908-8178903', '825-678901', '2019-10-10', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

INSERT INTO `ukeban` VALUES ('E01-1908-8178911', '825-678902', '2018-10-01', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `ukeban` VALUES ('E01-1908-8178912', '825-678902', '2019-10-01', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `ukeban` VALUES ('E01-1908-8178913', '825-678902', '2019-10-02', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

INSERT INTO `shujutsu` VALUES (0, '825-678901', 'E01-1908-8178901', '2019-08-01', '2019-08-02', '2019-11-29', 30, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `shujutsu` VALUES (0, '825-678902', 'E01-1908-8178911', '2019-11-01', '2019-11-02', '2020-02-29', 30, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

INSERT INTO `nyuin` VALUES (0, '825-678901', 'E01-1908-8178901', '2019-08-01', '2019-08-05', '2019-06-02', '2020-12-03', 30, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `nyuin` VALUES (0, '825-678902', 'E01-1908-8178912', '2019-11-01', '2019-11-10', '2019-09-02', '2020-03-04', 30, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

INSERT INTO `tsuin` VALUES (0, '825-678902', 'E01-1908-8178913', '2019-09-01', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `tsuin` VALUES (0, '825-678902', 'E01-1908-8178913', '2019-10-10', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `tsuin` VALUES (0, '825-678902', 'E01-1908-8178913', '2019-11-10', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `tsuin` VALUES (0, '825-678901', 'E01-1908-8178903', '2019-11-11', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `tsuin` VALUES (0, '825-678901', 'E01-1908-8178903', '2019-11-20', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `tsuin` VALUES (0, '825-678901', 'E01-1908-8178903', '2019-11-30', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `tsuin` VALUES (0, '825-678901', 'E01-1908-8178903', '2019-12-10', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);
INSERT INTO `tsuin` VALUES (0, '825-678901', 'E01-1908-8178903', '2019-12-20', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL);

COMMIT;
