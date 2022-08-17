CREATE TABLE `profile` (
  `user_id` int NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `game`varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(100) NOT NULL
);

ALTER TABLE `profile`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE `profile`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
