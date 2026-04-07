# Teachers Block

A Moodle block plugin that displays teachers for students to view their contact information and courses.

## Description

The Teachers block displays a list of all teachers from courses that the student is enrolled in. For each teacher, it shows:

- Profile photo
- Full name (linked to profile)
- Email address
- Phone number (if available)
- List of courses they teach
- Message button to send a message

## Features

- **Teacher Information Display**: Shows comprehensive teacher details including photo, name, email, and phone
- **Course Listing**: Displays all courses where each teacher is assigned
- **Direct Messaging**: Quick access button to message teachers
- **Responsive Design**: Mobile-friendly layout
- **Customizable Title**: Block title can be customized per instance
- **Multiple Instances**: Can be added multiple times to the dashboard

## Requirements

- Moodle 4.3 or higher
- PHP 8.0 or higher

## Installation

1. Copy the `teachers` folder to `blocks/` directory in your Moodle installation
2. Login as administrator
3. Navigate to Site Administration > Notifications
4. Follow the installation prompts

## Usage

### Adding the Block

1. Navigate to your Dashboard
2. Turn editing on
3. Click "Add a block"
4. Select "Teachers"

### Configuration

1. Click the gear icon on the block
2. Configure the block settings:
   - **Block title**: Customize the title displayed on the block

## Permissions

The block uses standard Moodle capabilities:

- `block/teachers:addinstance` - Add a new Teachers block to a page
- `block/teachers:myaddinstance` - Add a new Teachers block to Dashboard

## Privacy

This plugin does not store any personal data. It only displays information about teachers that is already stored in Moodle's core tables.

## Support

For issues, questions, or contributions, please contact the plugin maintainer.

## License

This plugin is licensed under the GNU GPL v3 or later.

## Credits

Copyright (c) 2026

## Changelog

### Version 1.0 (2026021300)
- Initial release
- Display teachers with profile photos
- Show teacher contact information
- List courses taught by each teacher
- Message button for direct communication
