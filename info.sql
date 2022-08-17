CREATE TABLE `Gamers` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar (100) NOT NULL,
  `email` int(10) NOT NULL,
  `game` varchar (100) NOT NULL,
  PRIMARY KEY (user_id)
);
ALTER TABLE `Gamers`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE `Gamers`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
