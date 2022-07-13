SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `xxx_fl4g_xxx` (
  `flag` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `xxx_fl4g_xxx` (`flag`) VALUES ("flag{this_is_flag}");

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `secret_numb` varchar(50) ,
  `password` varchar(100)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `users` (`id`, `name`, `secret_numb`, `password`) VALUES
(1, 'yellow', 'aa', 'aaaaa'),
(2, 'blue',  'aa', 'aaaaa'),
(3, 'green',  'aa', 'aaaaa'),
(4, 'black',  'aa', 'aaaaa'),
(5, 'white',  'aa', 'aaaaa'),
(6, 'orange',  'aa', 'aaaaa'),
(7, 'pink',  'aa', 'aaaaa'),
(8, 'lime',  'aa', 'aaaaa'),
(9, 'tsu',  'aa', 'aaaaa'),
(10, 'purple',  'aa', 'aaaaa'),
(11, 'red',  'aa', 'aaaaa');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
