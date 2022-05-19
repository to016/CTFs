import os, base64
from tkinter import E
from PIL import Image, ImageMath
from io import BytesIO
import sys

generate = lambda x: os.urandom(x).hex()

def make_alpha(data):
	color = data.get('background', [255,255,255])

	try:
		dec_img = base64.b64decode(data.get('image').encode())

		image = Image.open(BytesIO(dec_img)).convert('RGBA')
		
		print(image, file=sys.stderr)

		img_bands = [band.convert('F') for band in image.split()]

		print(color, file=sys.stderr)



		alpha = ImageMath.eval(
			f'''float(
				max(
				max(
					max(
					difference1(red_band, {color[0]}),
					difference1(green_band, {color[1]})
					),
					difference1(blue_band, {color[2]})
				),
				max(
					max(
					difference2(red_band, {color[0]}),
					difference2(green_band, {color[1]})
					),
					difference2(blue_band, {color[2]})
				)
				)
			)''',
			difference1=lambda source, color: (source - color) / (255.0 - color),
			difference2=lambda source, color: (color - source) / color,
			red_band=img_bands[0],
			green_band=img_bands[1],
			blue_band=img_bands[2]
		)

		new_bands = [
			ImageMath.eval(
				'convert((image - color) / alpha + color, "L")',
				image=img_bands[i],
				color=color[i],
				alpha=alpha
			)
			for i in range(3)
		]

		new_bands.append(ImageMath.eval(
			'convert(alpha_band * alpha, "L")',
			alpha=alpha,
			alpha_band=img_bands[3]
		))

		new_image = Image.merge('RGBA', new_bands)
		background = Image.new('RGB', new_image.size, (0, 0, 0, 0))
		background.paste(new_image.convert('RGB'), mask=new_image)

		buffer = BytesIO()
		new_image.save(buffer, format='PNG')

		return {
			'image': f'data:image/png;base64,{base64.b64encode(buffer.getvalue()).decode()}'
		}, 200

	except Exception as e:
		print(E, file=sys.stderr)
		return '', 400
