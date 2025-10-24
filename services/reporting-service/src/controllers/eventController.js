import { asyncHandler } from '../utils/asyncHandler.js';
import * as eventService from '../services/eventService.js';

export const receiveEvent = asyncHandler(async (req, res) => {
  const event = req.body;
  if (!event?.type) {
    res.status(400).json({ message: 'Invalid event payload' });
    return;
  }

  await eventService.recordEvent(event);
  res.status(202).json({ message: 'Event accepted' });
});
