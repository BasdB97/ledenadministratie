-- users
INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$2pQf2MVVUWn4CtGFQEn9P.89Raj9OC.cT744p90RVP9lAORIGRkUC', 'admin'),
(2, 'secretaris', '$2y$10$6APhdOk0ENHkUYCDivUptO36ad4.M3.GNpgR77EsbIYGzRFyPW34q', 'secretaris'),
(3, 'penningmeester', '$2y$10$9sP18WCIL.On5aTUb9CqX.y53KGAw9ykrnwbmLXsqdrM5fiPzIcD.', 'penningmeester');

-- member_type
INSERT INTO `member_type` (`id`, `description`, `discount_percentage`, `minimum_age`, `maximum_age`) VALUES
(1, 'Jeugd', 55.00, 0, 7),
(2, 'Aspirant', 40.00, 8, 12),
(3, 'Junior', 25.00, 13, 17),
(4, 'Senior', 0.00, 18, 50),
(5, 'Oudere', 45.00, 51, NULL);

-- bookyear
INSERT INTO `bookyear` (`id`, `year`, `description`) VALUES (1, 2025, 'Boekjaar 2025');

-- family
INSERT INTO `family` (`id`, `last_name`, `street`, `house_number`, `postal_code`, `city`, `country`) VALUES (1, 'Jansen', 'Hoofdweg', '23', '1234AB', 'Amsterdam', 'Nederland');

-- family_members
INSERT INTO `family_members` (`id`, `first_name`, `date_of_birth`, `age`, `member_type_id`, `family_id`) VALUES
(2, 'Anna', '2010-03-15', 15, 3, 1),
(3, 'Ben', '1995-07-22', 29, 4, 1),
(4, 'Clara', '1980-11-30', 44, 4, 1),
(5, 'David', '1965-04-10', 60, 5, 1),
(6, 'Emma', '2018-09-05', 6, 1, 1);

-- contributions
INSERT INTO `contributions` (`id`, `member_id`, `amount`, `member_type`, `bookyear_id`) VALUES
(1, 2, 75.00, 3, 1),
(2, 3, 100.00, 4, 1),
(3, 4, 100.00, 4, 1),
(4, 5, 55.00, 5, 1),
(5, 6, 45.00, 1, 1);

