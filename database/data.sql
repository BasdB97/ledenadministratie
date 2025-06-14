-- users
INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$2pQf2MVVUWn4CtGFQEn9P.89Raj9OC.cT744p90RVP9lAORIGRkUC', 'admin'),
(2, 'secretaris', '$2y$10$6APhdOk0ENHkUYCDivUptO36ad4.M3.GNpgR77EsbIYGzRFyPW34q', 'secretaris'),
(3, 'penningmeester', '$2y$10$9sP18WCIL.On5aTUb9CqX.y53KGAw9ykrnwbmLXsqdrM5fiPzIcD.', 'penningmeester');

-- bookyear
INSERT INTO `bookyear` (`id`, `year`, `description`) VALUES (1, 2025, 'Boekjaar 2025');

-- member_type
INSERT INTO `member_type` (`id`, `description`, `discount_percentage`, `minimum_age`, `maximum_age`) VALUES
(1, 'Jeugd', 55.00, 0, 7),
(2, 'Aspirant', 40.00, 8, 12),
(3, 'Junior', 25.00, 13, 17),
(4, 'Senior', 0.00, 18, 50),
(5, 'Oudere', 45.00, 51, NULL);

-- family
INSERT INTO `family` (`id`, `last_name`, `street`, `house_number`, `postal_code`, `city`, `country`) VALUES (1, 'Jansen', 'Hoofdweg', '23', '1234AB', 'Amsterdam', 'Nederland');

-- family_members
INSERT INTO `family_members` (`id`, `first_name`, `date_of_birth`, `age`, `family_member_type`, `member_type_id`, `family_id`) VALUES
(1, 'Anna', '2010-03-15', 15, 'dochter', 3, 1),
(2, 'Ben', '1995-07-22', 29, 'neef', 4, 1),
(3, 'Clara', '1980-11-30', 44, 'moeder', 4, 1),
(4, 'David', '1965-04-10', 60, 'vader', 5, 1),
(5, 'Robert', '2013-01-01', 15, 'zoon', 2, 1),
(6, 'Emma', '2018-09-05', 6, 'nicht', 1, 1);

-- contributions
INSERT INTO `contributions` (`id`, `member_id`, `amount`, `member_type`, `bookyear_id`) VALUES
(1, 1, 75.00, 3, 1),
(2, 2, 100.00, 4, 1),
(3, 3, 100.00, 4, 1),
(4, 4, 55.00, 5, 1),
(5, 5, 60.00, 1, 1),
(6, 6, 45.00, 1, 1);