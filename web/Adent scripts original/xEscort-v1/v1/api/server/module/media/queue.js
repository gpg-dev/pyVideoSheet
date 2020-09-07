const path = require('path');
const config = require('./config');
const Queue = require('../../kernel/services/queue');

const mediaQ = Queue.create('media');
const Video = require('./components/video');

mediaQ.process(async (job, done) => {
  const data = job.data.data;
  const command = job.data.command;
  try {
    if (command === 'convert-mp4') {
      const canPlay = await Video.canPlayInBrowser(data.filePath);
      if (!canPlay) {
        await DB.Media.update({
          _id: data.mediaId
        }, {
          $set: {
            convertStatus: 'processing'
          }
        });
        const convertFileName = await Video.toMp4({
          filePath: data.filePath
        });

        await DB.Media.update({
          _id: data.mediaId
        }, {
          $set: {
            filePath: path.join(config.videoDir, convertFileName),
            mimeType: 'video/mp4',
            convertStatus: 'done'
          }
        });
      }
    }

    done();
  } catch (e) {
    if (command === 'convert-mp4') {
      await DB.Media.update({
        _id: data.mediaId
      }, {
        $set: {
          convertStatus: 'failed'
        }
      });
    }
    done();
  }
});
